<?php

namespace App\Services;

use App\Models\User;
use App\Models\Car;
use App\Models\Reservation;
use App\Models\Review;
use Carbon\Carbon;
use Phpml\Classification\KNearestNeighbors;

class RecommendationService
{
    // Metoda do uzyskiwania rekomendacji dla użytkownika
    public function getRecommendations(User $user)
    {
        // Sprawdź, czy użytkownik ma jakiekolwiek rezerwacje
        $reservations = Reservation::where('user_id', $user->id)->exists();

        if (!$reservations) {
            // Użytkownik nie ma żadnych wypożyczeń, zwróć rekomendacje dla nowego użytkownika
            return $this->getRecommendationsForNewUser($user);
        }

        // Użytkownik ma wypożyczenia, zwróć rekomendacje dla istniejącego użytkownika
        return $this->getRecommendationsForExistingUser($user);
    }

    private function getRecommendationsForExistingUser(User $user)
    {
        // Pobierz wszystkie rezerwacje użytkownika
        $reservations = Reservation::where('user_id', $user->id)->get();
        $carIds = $reservations->pluck('car_id')->toArray();

        // Pobierz szczegóły samochodów, które użytkownik wypożyczył
        $carsUserRented = Car::whereIn('id', $carIds)->get();

        // Zbierz identyfikatory typów samochodów, które użytkownik wypożyczył
        $carTypesUserRented = $carsUserRented->pluck('car_body')->toArray();

        // Pobierz rekomendacje na podstawie najlepiej ocenianych samochodów w różnych typach
        $recommendedCars = collect();

        // Iteruj przez wszystkie typy samochodów, które użytkownik wypożyczył
        foreach ($carTypesUserRented as $carType) {
            // Pobierz samochody tego typu, niezależnie od tego, czy mają recenzje
            $cars = Car::where('car_body', $carType)->get();

            // Sortowanie samochodów według średniej oceny, jeśli dostępna
            $carsSortedByRating = $cars->sortByDesc(function ($car) {
                return $car->reviews->avg('overall_rating') ?? 0;
            })->take(2); // Można zmienić na więcej samochodów

            $recommendedCars = $recommendedCars->merge($carsSortedByRating);
        }

        // Usuń z rekomendacji samochody, które użytkownik już wypożyczył
        $recommendedCars = $recommendedCars->reject(function ($car) use ($carIds) {
            return in_array($car->id, $carIds);
        });

        // Dodatkowo, polecaj inne samochody z najlepszą oceną w tym samym typie
        foreach ($carTypesUserRented as $carType) {
            $cars = Car::where('car_body', $carType)->get();

            // Sortowanie samochodów według średniej oceny, jeśli dostępna
            $carsSortedByRating = $cars->sortByDesc(function ($car) {
                return $car->reviews->avg('overall_rating') ?? 0;
            })->take(2); // Można zmienić na więcej samochodów

            $recommendedCars = $recommendedCars->merge($carsSortedByRating);
        }

        return $recommendedCars->unique('id')->take(5); // Zwróć unikalne samochody, ogranicz do 5 rekomendacji
    }

    private function getRecommendationsForNewUser(User $user)
    {
        // Znajdź podobnych użytkowników na podstawie płci i wieku
        $similarUsers = User::where('sex', $user->sex)
                            ->whereBetween('birth', [
                                Carbon::parse($user->birth)->subYears(5)->format('Y-m-d'),
                                Carbon::parse($user->birth)->addYears(5)->format('Y-m-d')
                            ])
                            ->get();

        // Kolekcja na rekomendowane samochody
        $recommendedCars = collect();

        if (!$similarUsers->isEmpty()) {
            // Pobierz identyfikatory samochodów wypożyczonych przez podobnych użytkowników
            $similarUserIds = $similarUsers->pluck('id')->toArray();

            // Pobierz wszystkie wypożyczenia dla podobnych użytkowników
            $similarUserReservations = Reservation::whereIn('user_id', $similarUserIds)->get();

            // Pobierz unikalne identyfikatory samochodów wypożyczonych przez podobnych użytkowników
            $userRentals = $similarUserReservations->unique('car_id')->pluck('car_id')->toArray();

            // Pobierz szczegóły samochodów na podstawie identyfikatorów
            $recommendedCars = Car::whereIn('id', $userRentals)->get();
        }

        // Jeśli nie ma rekomendowanych samochodów na podstawie podobnych użytkowników,
        // dodaj najpopularniejsze i najlepiej oceniane samochody
        if ($recommendedCars->isEmpty()) {
            return $this->getMostPopularCars()->merge($this->getTopRatedCars())->unique('id')->take(5);
        }

        // Dodaj najpopularniejsze i najlepiej oceniane samochody do listy rekomendacji
        $popularAndTopRatedCars = $this->getMostPopularCars()->merge($this->getTopRatedCars());

        // Połączenie unikalnych rekomendowanych samochodów i ograniczenie do 5 wyników
        return $recommendedCars->merge($popularAndTopRatedCars)->unique('id')->take(3);
    }

    public function getMostPopularCars()
    {
        $popularCars = Reservation::select('car_id')
            ->selectRaw('COUNT(*) as total_reservations')
            ->groupBy('car_id')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(5)
            ->get();

        $carIds = $popularCars->pluck('car_id')->toArray();

        // Pobierz szczegóły samochodów na podstawie ich identyfikatorów
        $recommendedCars = Car::whereIn('id', $carIds)->get();

        return $recommendedCars;
    }

    public function getTopRatedCars()
    {
        $topRatedCars = Review::select('car_id')
            ->selectRaw('AVG(overall_rating) as avg_rating')
            ->groupBy('car_id')
            ->orderByRaw('AVG(overall_rating) DESC')
            ->limit(5)
            ->get();

        $carIds = $topRatedCars->pluck('car_id')->toArray();

        // Pobierz szczegóły samochodów na podstawie ich identyfikatorów
        $recommendedCars = Car::whereIn('id', $carIds)->get();

        return $recommendedCars;
    }

    /*
    // Metoda do trenowania modelu KNN
    public function trainKNNModel($trainingData, $labels)
    {
        $classifier = new KNearestNeighbors();
        $classifier->train($trainingData, $labels);

        return $classifier;
    }
    */
}
