<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Services\RecommendationServiceManager;
use App\Services\RecommendationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

class WelcomeController extends Controller
{
    protected $recommendationServiceManager;

    public function __construct(RecommendationServiceManager $recommendationServiceManager)
    {
        $this->recommendationServiceManager = $recommendationServiceManager;
    }

    public function index(Request $request)
    {
        $query = Car::query();

        if ($request->filled('brand')) {
            $query->where('brand', 'like', '%' . $request->brand . '%');
        }

        if ($request->filled('model')) {
            $query->where('model', 'like', '%' . $request->model . '%');
        }

        if ($request->filled('car_body')) {
            $query->where('car_body', $request->car_body);
        }

        if ($request->filled('engine_type')) {
            $query->where('engine_type', $request->engine_type);
        }

        if ($request->filled('transmission')) {
            $query->where('transmission', $request->transmission);
        }

        if ($request->filled('min_power')) {
            $query->where('engine_power', '>=', $request->min_power);
        }

        if ($request->filled('max_power')) {
            $query->where('engine_power', '<=', $request->max_power);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $cars = $query->get();

        $recommendedCars = [];
        if (Auth::check()) {
            $user = Auth::user();

            // Pobierz odpowiedni serwis rekomendacji z menedżera
            $recommendationService = $this->recommendationServiceManager->getService();

            // Pobierz rekomendacje na podstawie użytkownika
            $recommendedCarIds = $recommendationService->getRecommendations($user);

            // Pobierz samochody, które zostały zarekomendowane
            $recommendedCars = Car::whereIn('id', $recommendedCarIds)->get();
        }

        // Zaktualizuj dostępność samochodów (można to zrealizować jako zadanie Artisan)
        Artisan::call('update:car-availability');

        // Zwróć widok
        return view('welcome', compact('cars', 'recommendedCars'));
    }
}
