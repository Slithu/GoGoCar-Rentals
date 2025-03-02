<?php

namespace App\Services;

use Phpml\Classification\MLPClassifier;
use Phpml\NeuralNetwork\ActivationFunction\Sigmoid;
use Phpml\NeuralNetwork\ActivationFunction\PReLU;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Reservation;
use App\Models\Review;
use App\Models\Car;
use App\Models\RecommendationAlgorithm;

class RecommendationService3
{
    private $sexMapping;
    private $carBodyMapping;

    public function __construct()
    {
        $this->sexMapping = [
            'male' => 0,
            'female' => 1
        ];

        $this->carBodyMapping = [
            'Small Car' => 0,
            'Coupe' => 1,
            'Convertible' => 2,
            'Hatchback' => 3,
            'Estate Car' => 4,
            'Sedan' => 5,
            'SUV' => 6,
            'Minivan' => 7
        ];
    }

    public function getRecommendations($user)
    {
        return $this->recommendCars($user->id);
    }

    private function recommendCars($userId)
    {
        $users = $this->getUsers();
        $reservations = $this->getReservations();

        $currentUser = $users->find($userId);
        if (!$currentUser) {
            throw new \Exception("User not found.");
        }

        $userReservations = $reservations->where('user_id', $userId);

        if ($userReservations->isEmpty()) {
            return $this->recommendForNewUser($users, $reservations, $currentUser);
        } else {
            return $this->recommendForExistingUser();
        }
    }

    private function prepareTrainingData($users, $reservations)
    {
        $trainingData = [];
        $labels = [];

        foreach ($reservations as $reservation) {
            $user = $users->find($reservation->user_id);
            if (!$user) continue;

            $age = $this->calculateAge($user->birth);
            $sexEncoded = $this->encodeSex($user->sex);
            $userFeatures = [$age ?? 0, $sexEncoded];

            $car = $reservation->car;
            $carBodyFeatureEncoded = $this->encodeCarBody($car->car_body);
            $carRatings = $this->getCarRatings($car->id);

            if ($carBodyFeatureEncoded !== null) {
                $trainingData[] = array_merge($userFeatures, [
                    $carBodyFeatureEncoded,
                    $carRatings['comfort'],
                    $carRatings['driving_experience'],
                    $carRatings['fuel_efficiency'],
                    $carRatings['safety'],
                    $carRatings['overall']
                ]);
                $labels[] = $reservation->car_id;
            }
        }

        Log::info('Training Data: ', $trainingData);
        Log::info('Labels: ', $labels);

        return [$trainingData, $labels];
    }

    private function recommendForNewUser($users, $reservations, $currentUser)
    {
        // Przygotuj dane dla obecnego użytkownika
        $currentUserFeatures = [
            $this->calculateAge($currentUser->birth) ?? 0,
            $this->encodeSex($currentUser->sex),
            0, // Domyślna wartość dla car_body
            0, // Domyślna wartość dla comfort
            0, // Domyślna wartość dla driving_experience
            0, // Domyślna wartość dla fuel_efficiency
            0, // Domyślna wartość dla safety
            0  // Domyślna wartość dla overall
        ];

        // Szukaj podobnych użytkowników
        $similarUsers = $users->filter(function ($user) use ($currentUserFeatures) {
            return $user->id !== Auth::id() && (
                $this->encodeSex($user->sex) === $currentUserFeatures[1] &&
                abs($this->calculateAge($user->birth) - $currentUserFeatures[0]) <= 5
            );
        });

        // Przygotuj dane treningowe
        list($trainingData, $labels) = $this->prepareTrainingData($similarUsers, $reservations);

        // Sprawdź, czy dane treningowe są puste
        if (empty($trainingData)) {
            Log::info('No training data available. Returning best rated and most rented cars.');
            return $this->recommendBestRatedAndMostRentedCars();
        }

        // Sprawdź, czy liczba cech jest spójna
        $numFeatures = count($trainingData[0]);
        if (count($currentUserFeatures) !== $numFeatures) {
            throw new \Exception("Number of features in prediction data does not match training data.");
        }

        // Pobieranie aktywnego algorytmu na podstawie pola is_active lub domyślnego algorytmu
        $activeAlgorithm = RecommendationAlgorithm::where('is_active', true)
        ->first() ?? RecommendationAlgorithm::where('algorithm_name', 'naive_bayes')->first();

        // Pobieranie parametrów MLP z bazy danych
        $mlpHiddenLayer1 = $activeAlgorithm->mlp_hidden_layer_1 ?? 8;  // Domyślnie 8, jeśli brak w bazie
        $mlpHiddenLayer2 = $activeAlgorithm->mlp_hidden_layer_2 ?? 8;  // Domyślnie 8, jeśli brak w bazie
        $mlpIterations = $activeAlgorithm->mlp_iterations ?? 1000;     // Domyślnie 1000, jeśli brak w bazie

        // Konstrukcja modelu MLPClassifier
        $classifier = new MLPClassifier(
            (int) count($trainingData[0]),                 // neurony w warstwie wejściowej (cechy)
            [
                [$mlpHiddenLayer1, new PReLU()],           // Pierwsza warstwa ukryta z 8 neuronami używającymi aktywacji PReLU
                [$mlpHiddenLayer2, new Sigmoid()]          // Druga warstwa ukryta z 8 neuronami używającymi aktywacji Sigmoid
            ],
            array_unique($labels),                          // Unikalne etykiety wyjściowe (ID samochodów)
            $mlpIterations                                  // Liczba iteracji
        );

        $classifier->train($trainingData, $labels);
        $classifier->setLearningRate(0.1);

        if ($similarUsers->isNotEmpty()) {
            $similarUserIds = $similarUsers->pluck('id')->toArray();
            $reservationsBySimilarUsers = $reservations->whereIn('user_id', $similarUserIds);

            if ($reservationsBySimilarUsers->isEmpty()) {
                Log::info('Similar users have no reservations. Returning most rented cars.');
                return $this->recommendMostRentedCars();
            }

            $carRecommendations = $this->getCarRecommendationsFromReservations($reservationsBySimilarUsers);
            Log::info('Car Recommendations from Similar Users:', ['recommendedCarIds' => $carRecommendations]);

            // Dodaj samochód z największą ilością wypożyczeń
            $mostRentedCar = $this->getMostRentedCar($reservations);
            if ($mostRentedCar) {
                $carRecommendations[] = $mostRentedCar->id;
            }

            return $carRecommendations;
        } else {
            // Jeśli brak podobnych użytkowników, użyj modelu do przewidywań
            try {
                $predictedCarIds = $classifier->predict([$currentUserFeatures]);

                $recommendedCars = Car::whereIn('id', $predictedCarIds)->get();

                if ($recommendedCars->isNotEmpty()) {
                    $topCarIds = $recommendedCars->pluck('id')->take(3)->toArray();

                    if (count($topCarIds) < 3) {
                        $additionalCars = $this->recommendMostRentedCars();
                        $topCarIds = array_merge($topCarIds, $additionalCars);
                        $topCarIds = array_unique($topCarIds);
                        $topCarIds = array_slice($topCarIds, 0, 3);
                    }

                    // Dodaj samochód z największą ilością wypożyczeń
                    $mostRentedCar = $this->getMostRentedCar($reservations);
                    if ($mostRentedCar) {
                        $topCarIds[] = $mostRentedCar->id;
                    }

                    Log::info('Car Recommendations for New User from Model:', ['recommendedCarIds' => $topCarIds]);
                    return $topCarIds;
                } else {
                    Log::info('Model did not provide recommendations. Falling back to most rented cars.');
                    return $this->recommendMostRentedCars();
                }
            } catch (\Exception $e) {
                Log::error('Error in predicting car recommendations for new user:', ['error' => $e->getMessage()]);
                return $this->recommendMostRentedCars();
            }
        }
    }

    private function getMostRentedCar($reservations)
    {
        // Grupa samochodów po ID i zlicz ilość wypożyczeń
        $carReservations = $reservations->groupBy('car_id')->map(function ($group) {
            return $group->count();
        });

        // Znajdź samochód z największą ilością wypożyczeń
        $mostRentedCarId = $carReservations->sortDesc()->keys()->first();

        return Car::find($mostRentedCarId);
    }

    private function recommendMostRentedCars()
    {
        // Tutaj możesz stworzyć logikę do rekomendowania samochodów z największą ilością wypożyczeń
        $mostRentedCars = Car::all()->sortByDesc(function ($car) {
            return $car->reservations->count();
        })->take(3);

        return $mostRentedCars->pluck('id')->toArray();
    }

    private function getCarRecommendationsFromReservations($reservations)
    {
        $carRecommendations = [];
        foreach ($reservations as $reservation) {
            $carId = $reservation->car_id;
            $carRecommendations[$carId] = ($carRecommendations[$carId] ?? 0) + 1;
        }

        arsort($carRecommendations);
        return array_slice(array_keys($carRecommendations), 0, 10); // Zwróć top 10 samochodów
    }

    private function recommendForExistingUser()
    {
        $userId = Auth::id();
        $userReservations = Reservation::where('user_id', $userId)
            ->join('cars', 'reservations.car_id', '=', 'cars.id')
            ->get();

        if ($userReservations->isEmpty()) {
            // Jeśli użytkownik nie ma żadnych rezerwacji, przekazujemy dane do funkcji dla nowych użytkowników
            $users = $this->getUsers();
            $reservations = $this->getReservations();
            $currentUser = $users->find($userId);

            return $this->recommendForNewUser($users, $reservations, $currentUser);
        }

        // Przygotowanie danych do klasyfikacji
        $users = $this->getUsers();
        $reservations = $this->getReservations();

        // Przygotowanie danych dla obecnego użytkownika
        $currentUser = $users->find($userId);
        $currentUserFeatures = [
            $this->calculateAge($currentUser->birth) ?? 0,
            $this->encodeSex($currentUser->sex),
            0, // Domyślna wartość dla car_body
            0, // Domyślna wartość dla comfort
            0, // Domyślna wartość dla driving_experience
            0, // Domyślna wartość dla fuel_efficiency
            0, // Domyślna wartość dla safety
            0  // Domyślna wartość dla overall
        ];

        // Filtrujemy użytkowników, którzy są tej samej płci i w podobnym wieku (+/- 5 lat)
        $similarUsers = $users->filter(function ($user) use ($currentUser) {
            return $user->id !== $currentUser->id && (
                $this->encodeSex($user->sex) === $this->encodeSex($currentUser->sex) &&  // ta sama płeć
                abs($this->calculateAge($user->birth) - $this->calculateAge($currentUser->birth)) <= 5 // wiek w przedziale 5 lat
            );
        });

        // Jeśli podobni użytkownicy istnieją, przechodzimy do klasyfikacji
        if ($similarUsers->isNotEmpty()) {
            // Przygotowanie danych treningowych na podstawie rezerwacji podobnych użytkowników
            list($trainingData, $labels) = $this->prepareTrainingData($similarUsers, $reservations);

            if (empty($trainingData) || empty($labels)) {
                // Jeśli nie ma danych treningowych, zwróć rekomendacje na podstawie typów nadwozia użytkownika
                Log::info('No training data for classifier. Returning cars based on user\'s past reservations.');
                return $this->recommendCarsByUserReservations($userReservations);
            }

            $uniqueLabels = array_unique($labels);
            if (count($uniqueLabels) < 2) {
                // Jeżeli mamy tylko jedną klasę, zwróć błąd lub przekaż inne dane treningowe
                Log::error('Training data contains only one class. Cannot train the model.');
                return $this->recommendCarsByUserReservations($userReservations); // Lub inne zachowanie w przypadku braku różnorodnych klas
            }

            // Pobieranie aktywnego algorytmu na podstawie pola is_active lub domyślnego algorytmu
            $activeAlgorithm = RecommendationAlgorithm::where('is_active', true)
            ->first() ?? RecommendationAlgorithm::where('algorithm_name', 'naive_bayes')->first();

            // Pobieranie parametrów MLP z bazy danych
            $mlpHiddenLayer1 = $activeAlgorithm->mlp_hidden_layer_1 ?? 8;  // Domyślnie 8, jeśli brak w bazie
            $mlpHiddenLayer2 = $activeAlgorithm->mlp_hidden_layer_2 ?? 8;  // Domyślnie 8, jeśli brak w bazie
            $mlpIterations = $activeAlgorithm->mlp_iterations ?? 1000;     // Domyślnie 1000, jeśli brak w bazie

            $classifier = new MLPClassifier(
                (int) count($trainingData[0]),                 // neurony w warstwie wejściowej (cechy)
                [
                    [$mlpHiddenLayer1, new PReLU()],           // Pierwsza warstwa ukryta z 8 neuronami używającymi aktywacji PReLU
                    [$mlpHiddenLayer2, new Sigmoid()]          // Druga warstwa ukryta z 8 neuronami używającymi aktywacji Sigmoid
                ],
                array_unique($labels),                          // Unikalne etykiety wyjściowe (ID samochodów)
                $mlpIterations                                  // Liczba iteracji
            );

            $classifier->train($trainingData, $labels);
            $classifier->setLearningRate(0.1);

            // Predykcja dla obecnego użytkownika
            try {
                $predictedCarIds = $classifier->predict([$currentUserFeatures]);

                // Pobierz samochody, które są zgodne z przewidywanymi ID
                $recommendedCars = Car::whereIn('id', $predictedCarIds)->get();

                // Jeśli model zwrócił mniej niż 3 samochody, dodaj rekomendacje na podstawie typów nadwozia użytkownika
                if ($recommendedCars->count() < 3) {
                    $carBodies = $userReservations->pluck('car_body')->unique()->toArray();

                    $additionalCars = [];
                    foreach ($carBodies as $bodyType) {
                        // Szukamy samochodów, które mają ten sam typ nadwozia
                        $additionalCars = array_merge($additionalCars, Car::where('car_body', $bodyType)
                            ->leftJoin('reviews', 'cars.id', '=', 'reviews.car_id')
                            ->select('cars.id')
                            ->groupBy('cars.id')
                            ->orderByRaw('COALESCE(AVG(reviews.overall_rating), 0) DESC')
                            ->take(3)  // Możemy wziąć top 3 samochody tego typu
                            ->pluck('cars.id')
                            ->toArray());
                    }

                    // Łączymy rekomendacje z modelu z dodatkowymi samochodami
                    $recommendedCarIds = array_merge($predictedCarIds, $additionalCars);
                    $recommendedCarIds = array_unique($recommendedCarIds);  // Unikalne samochody
                    $recommendedCarIds = array_slice($recommendedCarIds, 0, 3);  // Tylko 3 rekomendacje
                } else {
                    // Jeśli model zwrócił wystarczającą liczbę samochodów, zwróć je
                    $recommendedCarIds = $predictedCarIds;
                }

                Log::info('Car Recommendations for Existing User (Similar Users):', ['recommendedCarIds' => $recommendedCarIds]);

                return $recommendedCarIds;

            } catch (\Exception $e) {
                Log::error('Error in predicting car recommendations for existing user:', ['error' => $e->getMessage()]);
                // W przypadku błędu w przewidywaniu, zwróć rekomendacje na podstawie typów nadwozia użytkownika
                return $this->recommendCarsByUserReservations($userReservations);
            }
        } else {
            // Jeśli brak podobnych użytkowników, zwróć rekomendacje na podstawie typów nadwozia użytkownika
            Log::info('No similar users found. Returning cars based on user\'s past reservations.');
            return $this->recommendCarsByUserReservations($userReservations);
        }
    }

    // Funkcja do rekomendacji samochodów na podstawie typów nadwozia, które użytkownik już wypożyczył
    private function recommendCarsByUserReservations($userReservations)
    {
        $carBodies = $userReservations->pluck('car_body')->unique()->toArray();

        $recommendedCarIds = [];
        foreach ($carBodies as $bodyType) {
            // Szukamy samochodów, które mają ten sam typ nadwozia
            $recommendedCarIds = array_merge($recommendedCarIds, Car::where('car_body', $bodyType)
                ->leftJoin('reviews', 'cars.id', '=', 'reviews.car_id')
                ->select('cars.id')
                ->groupBy('cars.id')
                ->orderByRaw('COALESCE(AVG(reviews.overall_rating), 0) DESC')
                ->take(3)  // Możemy wziąć top 3 samochody tego typu
                ->pluck('cars.id')
                ->toArray());
        }

        return array_unique($recommendedCarIds);  // Unikalne samochody
    }

    private function recommendBestRatedAndMostRentedCars()
    {
        $carRatings = [];
        $carCounts = [];

        $reservations = $this->getReservations();
        foreach ($reservations as $reservation) {
            $car = $reservation->car;
            if (!isset($carRatings[$car->id])) {
                $carRatings[$car->id] = [
                    'comfort' => 0,
                    'driving_experience' => 0,
                    'fuel_efficiency' => 0,
                    'safety' => 0,
                    'overall' => 0,
                ];
                $carCounts[$car->id] = 0;
            }

            $carRatings[$car->id]['comfort'] += $reservation->comfort_rating;
            $carRatings[$car->id]['driving_experience'] += $reservation->driving_experience_rating;
            $carRatings[$car->id]['fuel_efficiency'] += $reservation->fuel_efficiency_rating;
            $carRatings[$car->id]['safety'] += $reservation->safety_rating;
            $carRatings[$car->id]['overall'] += $reservation->overall_rating;
            $carCounts[$car->id]++;
        }

        // Obliczanie średnich ocen
        $averageRatings = [];
        foreach ($carRatings as $carId => $ratings) {
            foreach ($ratings as $feature => $totalRating) {
                $averageRatings[$carId][$feature] = $totalRating / $carCounts[$carId];
            }
        }

        // Posortowanie samochodów według najlepszych średnich ocen
        arsort($averageRatings);
        $topCarIds = array_keys($averageRatings);

        return array_slice($topCarIds, 0, 3);
    }

    private function getCarRatings($carId)
    {
        $defaultRatings = [
            'comfort' => 0,
            'driving_experience' => 0,
            'fuel_efficiency' => 0,
            'safety' => 0,
            'overall' => 0
        ];

        $reviews = Review::where('car_id', $carId)->get();
        if ($reviews->isEmpty()) {
            return $defaultRatings;
        }

        $ratings = [
            'comfort' => 0,
            'driving_experience' => 0,
            'fuel_efficiency' => 0,
            'safety' => 0,
            'overall' => 0
        ];

        foreach ($reviews as $review) {
            $ratings['comfort'] += (float) $review->comfort_rating;
            $ratings['driving_experience'] += (float) $review->driving_experience_rating;
            $ratings['fuel_efficiency'] += (float) $review->fuel_efficiency_rating;
            $ratings['safety'] += (float) $review->safety_rating;
            $ratings['overall'] += (float) $review->overall_rating;
        }

        $numReviews = $reviews->count();
        return [
            'comfort' => $ratings['comfort'] / $numReviews,
            'driving_experience' => $ratings['driving_experience'] / $numReviews,
            'fuel_efficiency' => $ratings['fuel_efficiency'] / $numReviews,
            'safety' => $ratings['safety'] / $numReviews,
            'overall' => $ratings['overall'] / $numReviews
        ];
    }

    private function encodeSex($sex)
    {
        return $this->sexMapping[$sex] ?? null;
    }

    private function encodeCarBody($carBody)
    {
        return $this->carBodyMapping[$carBody] ?? null;
    }

    private function calculateAge($birthDate)
    {
        return Carbon::parse($birthDate)->age;
    }

    private function getUsers()
    {
        return User::all();
    }

    private function getReservations()
    {
        return Reservation::with('car')->get();
    }
}
