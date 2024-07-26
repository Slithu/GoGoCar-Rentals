<?php

namespace App\Services;

use Phpml\Classification\NaiveBayes;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Reservation;
use App\Models\Review;
use App\Models\Car;

class RecommendationService
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
            // Jeśli użytkownik nie ma rezerwacji, wywołaj recommendForNewUser
            return $this->recommendForNewUser($users, $reservations, $currentUser);
        } else {
            // Jeśli użytkownik ma rezerwacje, wywołaj recommendForExistingUser
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
        // Przygotuj dane treningowe
        list($trainingData, $labels) = $this->prepareTrainingData($users, $reservations);

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

        // Sprawdź, czy liczba cech jest spójna
        $numFeatures = count($trainingData[0]);
        if (count($currentUserFeatures) !== $numFeatures) {
            throw new \Exception("Number of features in prediction data does not match training data.");
        }

        // Stwórz i wytrenuj model klasyfikacji
        $classifier = new NaiveBayes();
        $classifier->train($trainingData, $labels);

        // Szukaj podobnych użytkowników
        $similarUsers = $users->filter(function ($user) use ($currentUserFeatures) {
            return $user->id !== Auth::id() && (
                $this->encodeSex($user->sex) === $currentUserFeatures[1] &&
                abs($this->calculateAge($user->birth) - $currentUserFeatures[0]) <= 5
            );
        });

        if ($similarUsers->isNotEmpty()) {
            $similarUserIds = $similarUsers->pluck('id')->toArray();
            $reservationsBySimilarUsers = $reservations->whereIn('user_id', $similarUserIds);

            $carRecommendations = $this->getCarRecommendationsFromReservations($reservationsBySimilarUsers);
            Log::info('Car Recommendations from Similar Users:', ['recommendedCarIds' => $carRecommendations]);

            return $carRecommendations;
        } else {
            // Jeśli brak podobnych użytkowników, użyj modelu do przewidywań
            try {
                $predictedCarIds = $classifier->predict([$currentUserFeatures]);

                // Pobierz samochody, które są zgodne z przewidywanymi ID
                $recommendedCars = Car::whereIn('id', $predictedCarIds)->get();

                if ($recommendedCars->isNotEmpty()) {
                    // Jeśli model zwrócił mniej niż 3 samochody, dodaj rekomendacje na podstawie ocen i liczby wypożyczeń
                    $topCarIds = $recommendedCars->pluck('id')->take(3)->toArray();

                    if (count($topCarIds) < 3) {
                        // Dodaj dodatkowe rekomendacje, aby uzyskać 3 samochody
                        $additionalCars = $this->recommendBestRatedAndMostRentedCars();
                        $topCarIds = array_merge($topCarIds, $additionalCars);
                        $topCarIds = array_unique($topCarIds);
                        $topCarIds = array_slice($topCarIds, 0, 3);
                    }

                    Log::info('Car Recommendations for New User from Model:', ['recommendedCarIds' => $topCarIds]);
                    return $topCarIds;
                } else {
                    // Jeśli brak rekomendacji z modelu, użyj rekomendacji na podstawie ocen i liczby wypożyczeń
                    Log::info('Model did not provide recommendations. Falling back to best rated and most rented cars.');
                    return $this->recommendBestRatedAndMostRentedCars();
                }
            } catch (\Exception $e) {
                Log::error('Error in predicting car recommendations for new user:', ['error' => $e->getMessage()]);
                // W przypadku błędu w przewidywaniu, użyj rekomendacji na podstawie ocen i liczby wypożyczeń
                return $this->recommendBestRatedAndMostRentedCars();
            }
        }
    }

    private function recommendBestRatedAndMostRentedCars()
    {
        $carRatings = [];
        $carCounts = [];

        $reservations = $this->getReservations();
        foreach ($reservations as $reservation) {
            $carId = $reservation->car_id;

            if (!isset($carCounts[$carId])) {
                $carCounts[$carId] = 0;
                $carRatings[$carId] = $this->getCarRatings($carId);
            }

            $carCounts[$carId]++;
        }

        // Oblicz średnie oceny
        foreach ($carRatings as $carId => $ratings) {
            $numReviews = $carCounts[$carId];
            $carRatings[$carId] = [
                'comfort' => $ratings['comfort'] / $numReviews,
                'driving_experience' => $ratings['driving_experience'] / $numReviews,
                'fuel_efficiency' => $ratings['fuel_efficiency'] / $numReviews,
                'safety' => $ratings['safety'] / $numReviews,
                'overall' => $ratings['overall'] / $numReviews
            ];
        }

        // Sortuj samochody według liczby wypożyczeń i ocen
        $sortedCars = collect($carCounts)->sortByDesc(function ($count, $carId) use ($carRatings) {
            $ratings = $carRatings[$carId];
            return $count * $ratings['overall'];
        })->keys()->take(3)->toArray();

        Log::info('Best Rated and Most Rented Cars:', ['recommendedCarIds' => $sortedCars]);

        return array_slice($sortedCars, 0, 5);
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
        Log::info('Entering recommendForExistingUser method.');
        Log::info('Current User ID:', ['userId' => $userId]);

        // Pobierz typy nadwozia samochodów wypożyczonych przez użytkownika
        $carBodies = Reservation::where('user_id', $userId)
            ->join('cars', 'reservations.car_id', '=', 'cars.id')
            ->pluck('cars.car_body')
            ->unique()
            ->toArray();

        Log::info('Car Bodies:', ['carBodies' => $carBodies]);

        if (empty($carBodies)) {
            // Jeśli brak typów nadwozia, wywołaj recommendForNewUser
            Log::info('No car bodies found for user. Calling recommendForNewUser.');
            return $this->recommendForNewUser($this->getUsers(), $this->getReservations(), User::find($userId));
        }

        // Pobierz samochody, które są zgodne z typami nadwozia
        $recommendedCars = Car::whereIn('car_body', $carBodies)->get();

        Log::info('Recommended Cars:', ['recommendedCarIds' => $recommendedCars->pluck('id')->toArray()]);

        return $recommendedCars->pluck('id')->toArray();
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
        return $this->sexMapping[$sex] ?? 0;
    }

    private function encodeCarBody($carBody)
    {
        return $this->carBodyMapping[$carBody] ?? null;
    }

    private function calculateAge($birthDate)
    {
        return $birthDate ? Carbon::parse($birthDate)->age : null;
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
