<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Car;
use App\Models\User;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\RecommendationAlgorithm;
use App\Models\Review;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function index() : View
    {
        $algorithms = RecommendationAlgorithm::all();

        // Pobieranie aktywnego algorytmu
        $activeAlgorithm = RecommendationAlgorithm::getActiveAlgorithm();

        // Jeśli brak aktywnego algorytmu, ustawiamy domyślny
        if (!$activeAlgorithm) {
            $activeAlgorithm = RecommendationAlgorithm::where('algorithm_name', 'naive_bayes')->first();
        }

        // Paginowanie historii aktywacji i dezaktywacji
        foreach ($algorithms as $algorithm) {
            // Dekodowanie dat aktywacji i dezaktywacji
            $activationDates = json_decode($algorithm->activated_at, true) ?? [];
            $deactivationDates = json_decode($algorithm->deactivated_at, true) ?? [];

            // Tworzymy kolekcję z datami aktywacji
            $activationHistory = collect($activationDates)->map(function ($activationDate, $index) use ($deactivationDates) {
                return [
                    'activation' => \Carbon\Carbon::parse($activationDate)->format('Y-m-d'),
                    'deactivation' => isset($deactivationDates[$index])
                        ? \Carbon\Carbon::parse($deactivationDates[$index])->format('Y-m-d')
                        : 'No deactivation history'
                ];
            });

            // Paginacja kolekcji: 5 elementów na stronę
            $perPage = 5;
            $currentPage = request()->get('page', 1); // Numer strony z requesta
            $paginatedHistory = $activationHistory->forPage($currentPage, $perPage); // Paginuje dane

            // Ustawiamy paginowaną historię w algorytmie
            $algorithm->activationHistory = $paginatedHistory;

            // Ustawiamy także dane o całkowitej liczbie stron, żeby przekazać do widoku
            $algorithm->totalActivationHistory = $activationHistory->count();
            $algorithm->totalPages = ceil($algorithm->totalActivationHistory / $perPage); // Liczba stron
        }

        $accordionOpen = request()->get('accordion_open', null); // Zmienna dla otwartej sekcji akordeonu

        return view("admin.index", compact('algorithms', 'activeAlgorithm', 'accordionOpen'));
    }

    public function updateRecommendationSettings(Request $request)
    {
        // Walidacja danych wejściowych
        $request->validate([
            'active_algorithm' => 'required|string',
            'knn_k' => 'nullable|integer|min:1|max:100',
            'mlp_hidden_layer_1' => 'nullable|integer|min:1|max:100',
            'mlp_hidden_layer_2' => 'nullable|integer|min:1|max:100',
            'mlp_iterations' => 'nullable|integer|min:100|max:100000',
            'decision_tree_depth' => 'nullable|integer|min:1|max:50',
        ]);

        // Pobranie aktualnie aktywnego algorytmu
        $currentActiveAlgorithm = RecommendationAlgorithm::where('is_active', true)->first();

        // Jeśli zmienia się aktywny algorytm, zapisujemy datę deaktywacji
        if ($currentActiveAlgorithm && $currentActiveAlgorithm->algorithm_name !== $request->input('active_algorithm')) {
            $deactivatedAt = json_decode($currentActiveAlgorithm->deactivated_at, true) ?? [];
            $deactivatedAt[] = now()->toDateString(); // Dodajemy datę deaktywacji
            $currentActiveAlgorithm->deactivated_at = json_encode($deactivatedAt);
            $currentActiveAlgorithm->is_active = false; // Dezaktywujemy obecny algorytm
            $currentActiveAlgorithm->save(); // Zapisujemy zmiany
        }

        // Wyłączenie aktywności innych algorytmów
        RecommendationAlgorithm::where('is_active', true)->update(['is_active' => false]);

        // Pobranie lub stworzenie nowego algorytmu
        $newAlgorithm = RecommendationAlgorithm::where('algorithm_name', $request->input('active_algorithm'))->first();

        if (!$newAlgorithm) {
            // Jeśli algorytm nie istnieje, tworzymy nowy
            $newAlgorithm = new RecommendationAlgorithm();
            $newAlgorithm->algorithm_name = $request->input('active_algorithm');
            $newAlgorithm->activation_count = 1;
            $newAlgorithm->activated_at = json_encode([now()->toDateString()]); // Dodajemy datę aktywacji
        } else {
            // Jeśli algorytm istnieje, zwiększamy licznik aktywacji
            $newAlgorithm->activation_count += 1;

            // Jeżeli algorytm staje się aktywny, dodajemy datę aktywacji
            $activatedAt = json_decode($newAlgorithm->activated_at, true) ?? [];
            // Dodajemy datę aktywacji tylko wtedy, gdy zmienia się algorytm, a nie tylko jego parametry
            if ($newAlgorithm->algorithm_name !== $currentActiveAlgorithm->algorithm_name) {
                $activatedAt[] = now()->toDateString(); // Dodajemy nową datę aktywacji
            }
            $newAlgorithm->activated_at = json_encode($activatedAt);
        }

        // Ustawiamy parametry specyficzne dla algorytmu
        if ($request->input('active_algorithm') === 'knn') {
            $newAlgorithm->knn_k = $request->input('knn_k', 3);
        } elseif ($request->input('active_algorithm') === 'mlp') {
            $newAlgorithm->mlp_hidden_layer_1 = $request->input('mlp_hidden_layer_1', 8);
            $newAlgorithm->mlp_hidden_layer_2 = $request->input('mlp_hidden_layer_2', 8);
            $newAlgorithm->mlp_iterations = $request->input('mlp_iterations', 1000);
        } elseif ($request->input('active_algorithm') === 'decision_tree') {
            $newAlgorithm->decision_tree_depth = $request->input('decision_tree_depth', 10);
        }

        // Ustawiamy aktywność tego algorytmu
        $newAlgorithm->is_active = true;

        // Zapisujemy algorytm
        $newAlgorithm->save();

        return redirect()->route('admin.index')->with('success', 'Algorithm settings updated successfully.');
    }

    public function recommendCars(Request $request)
    {
        $userId = $request->user()->id;

        // Pobranie aktywnego algorytmu
        $activeAlgorithm = RecommendationAlgorithm::getActiveAlgorithm();

        // Logika rekomendacji (na podstawie aktywnego algorytmu)
        $recommendedCars = Car::recommendBasedOnAlgorithm($activeAlgorithm->algorithm_name, $userId);

        // Zwiększenie licznika rekomendacji
        $activeAlgorithm->increment('recommendations_count');

        return response()->json([
            'recommended_cars' => $recommendedCars,
            'algorithm' => $activeAlgorithm->algorithm_name,
        ]);
    }

    public function generateUsersReport()
    {
        $users = User::where('role', 'user')->get();
        $date = Carbon::now()->format('Y-m-d H:i:s');
        $pdf = PDF::loadView('admin.reports.users', compact('users'));
        return $pdf->download('users_report.pdf');
    }

    public function generateCarsReport()
    {
        $cars = Car::all();
        $date = Carbon::now()->format('Y-m-d H:i:s');
        $pdf = PDF::loadView('admin.reports.cars', compact('cars'));
        return $pdf->download('cars_report.pdf');
    }

    public function generateRentalsReport()
    {
        $reservations = Reservation::all();
        $users = User::all();
        $cars = Car::all();
        $date = Carbon::now()->addHours(2)->format('Y-m-d H:i:s');
        $pdf = PDF::loadView('admin.reports.rentals', compact('reservations', 'users', 'cars'));

        return $pdf->download('rentals_report.pdf');
    }

    public function generateDailyRentalsReport()
    {
        $today = Carbon::now()->startOfDay();
        $reservations = Reservation::whereDate('start_date', $today)->get();
        $userIds = $reservations->pluck('user_id')->unique();
        $carIds = $reservations->pluck('car_id')->unique();
        $users = User::whereIn('id', $userIds)->get();
        $cars = Car::whereIn('id', $carIds)->get();
        $date = Carbon::now()->addHours(2)->format('Y-m-d H:i:s');
        $pdf = PDF::loadView('admin.reports.daily_rentals', compact('reservations', 'users', 'cars', 'date'));

        return $pdf->download('daily_rentals_report' . $date . '.pdf');
    }

    public function generateWeeklyRentalsReport()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $reservations = Reservation::whereBetween('start_date', [$startOfWeek, $endOfWeek])->get();
        $userIds = $reservations->pluck('user_id')->unique();
        $carIds = $reservations->pluck('car_id')->unique();
        $users = User::whereIn('id', $userIds)->get();
        $cars = Car::whereIn('id', $carIds)->get();
        $date = Carbon::now()->addHours(2)->format('Y-m-d H:i:s');
        $pdf = PDF::loadView('admin.reports.weekly_rentals', compact('reservations', 'users', 'cars', 'date'));

        return $pdf->download('weekly_rentals_report' . $date . '.pdf');
    }

    public function generateMonthlyRentalsReport()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $reservations = Reservation::whereBetween('start_date', [$startOfMonth, $endOfMonth])->get();
        $userIds = $reservations->pluck('user_id')->unique();
        $carIds = $reservations->pluck('car_id')->unique();
        $users = User::whereIn('id', $userIds)->get();
        $cars = Car::whereIn('id', $carIds)->get();
        $date = Carbon::now()->addHours(2)->format('Y-m-d H:i:s');
        $pdf = PDF::loadView('admin.reports.monthly_rentals', compact('reservations', 'users', 'cars', 'date'));

        return $pdf->download('monthly_rentals_reports' . $date . '.pdf');
    }

    public function generateCarReviewsReport()
    {
        $reviews = Review::all();
        $users = User::all();
        $cars = Car::all();
        $date = Carbon::now()->format('Y-m-d H:i:s');
        $pdf = PDF::loadView('admin.reports.reviews', compact('reviews', 'users', 'cars'));
        return $pdf->download('car_reviews_report.pdf');
    }

    public function cars(Request $request): View
    {
        Artisan::call('update:car-availability');

        $search = $request->input('search');

        $query = Car::query();

        if ($search) {
            $searchTerms = explode(' ', $search);

            $query->where(function ($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $q->where(function ($query) use ($term) {
                        $query->where('brand', 'like', "%{$term}%")
                              ->orWhere('model', 'like', "%{$term}%");
                    });
                }
            });
        }

        $cars = $query->get();

        return view('admin.cars', [
            'cars' => $cars,
            'reservations' => Reservation::all(),
        ]);
    }

    public function usersChart()
    {
        $startDate = now()->addHour(2)->startOfYear();
        $endDate = now()->addHour(2);

        $monthlyUserCounts = User::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('role', 'user')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT),
                    'count' => $item->count,
                ];
            });

        $months = $monthlyUserCounts->pluck('month');
        $counts = $monthlyUserCounts->pluck('count');

        return view('admin.charts.users.users', compact('months', 'counts'));
    }

    public function ageChart()
    {
        $now = Carbon::now()->year;

        // Zakresy wiekowe
        $ageRanges = [
            '18-24' => [18, 24],
            '25-34' => [25, 34],
            '35-44' => [35, 44],
            '45-54' => [45, 54],
            '55-64' => [55, 64],
            '65+'   => [65, PHP_INT_MAX]
        ];

        $ageCounts = array_fill_keys(array_keys($ageRanges), 0);

        foreach (User::where('role', 'user')->get() as $user) {
            $birthDate = Carbon::parse($user->birth);
            $age = $now - $birthDate->year;

            foreach ($ageRanges as $label => $range) {
                if ($age >= $range[0] && $age <= $range[1]) {
                    $ageCounts[$label]++;
                    break;
                }
            }
        }

        $ageLabels = array_keys($ageCounts);
        $ageData = array_values($ageCounts);

        return view('admin.charts.users.age', compact('ageLabels', 'ageData'));
    }

    public function genderChart()
    {
        $genders = ['Male', 'Female'];

        $genderCounts = [
            'Male'   => User::where('sex', 'male')->where('role', 'user')->count(),
            'Female' => User::where('sex', 'female')->where('role', 'user')->count(),
        ];

        $genderLabels = array_keys($genderCounts);
        $genderData = array_values($genderCounts);

        return view('admin.charts.users.gender', compact('genderLabels', 'genderData'));
    }

    public function carsChart()
    {
        $startDate = now()->addHour(2)->startOfYear();
        $endDate = now()->addHour(2);

        $monthlyCarCounts = Car::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT),
                    'count' => $item->count,
                ];
            });

        $months = $monthlyCarCounts->pluck('month');
        $counts = $monthlyCarCounts->pluck('count');

        return view('admin.charts.cars', compact('months', 'counts'));
    }

    public function rentalsChart()
    {
        $startDate = now()->addHour(2)->startOfYear();
        $endDate = now()->addHour(2);

        $monthlyRentalCounts = Reservation::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->whereBetween('start_date', [$startDate, $endDate])
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT),
                    'count' => $item->count,
                ];
            });

        $months = $monthlyRentalCounts->pluck('month');
        $counts = $monthlyRentalCounts->pluck('count');

        return view('admin.charts.rentals.rentals', compact('months', 'counts'));
    }

    public function carBodyChart()
    {
        $carBodies = Reservation::select('cars.car_body', DB::raw('count(reservations.id) as count'))
            ->join('cars', 'reservations.car_id', '=', 'cars.id')
            ->groupBy('cars.car_body')
            ->pluck('count', 'car_body')
            ->toArray();

        $labels = array_keys($carBodies);
        $counts = array_values($carBodies);

        return view('admin.charts.rentals.car_body', compact('labels', 'counts'));
    }

    public function brandsChart()
    {
        $brandCounts = Reservation::join('cars', 'reservations.car_id', '=', 'cars.id')
            ->select('cars.brand', DB::raw('COUNT(reservations.id) as count'))
            ->groupBy('cars.brand')
            ->orderBy('count', 'desc')
            ->get();

        $brands = $brandCounts->pluck('brand');
        $counts = $brandCounts->pluck('count');

        return view('admin.charts.rentals.brands', compact('brands', 'counts'));
    }

    public function averagePriceChart()
    {
        $averagePrices = Reservation::selectRaw('YEAR(start_date) as year, MONTH(start_date) as month, AVG(total_price) as average_price')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $averagePrices = $averagePrices->map(function ($item) {
            return [
                'month' => $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT),
                'average_price' => round($item->average_price, 2),
            ];
        });

        $months = $averagePrices->pluck('month');
        $prices = $averagePrices->pluck('average_price');

        return view('admin.charts.rentals.average_price', compact('months', 'prices'));
    }

    public function rentalDurationChart()
    {
        $averageDurations = Reservation::selectRaw('YEAR(start_date) as year, MONTH(start_date) as month, AVG(DATEDIFF(end_date, start_date)) as average_duration')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $months = $averageDurations->map(function ($item) {
            return $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT);
        });

        $durations = $averageDurations->pluck('average_duration')->map(function ($duration) {
            return round($duration, 2);
        });

        return view('admin.charts.rentals.rental_duration', compact('months', 'durations'));
    }

    public function reviewsChart()
    {
        $startDate = now()->addHour(2)->startOfYear();
        $endDate = now()->addHour(2);

        $monthlyReviewCounts = Review::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT),
                    'count' => $item->count,
                ];
            });

        $months = $monthlyReviewCounts->pluck('month');
        $counts = $monthlyReviewCounts->pluck('count');

        return view('admin.charts.reviews', compact('months', 'counts'));
    }

    public function averageReviewsChart()
    {
        $averageRatings = Review::selectRaw('car_id, AVG(overall_rating) as average_rating')
            ->groupBy('car_id')
            ->orderBy('average_rating', 'desc')
            ->get();

        $carDetails = Car::whereIn('id', $averageRatings->pluck('car_id'))
            ->select('id', 'brand', 'model')
            ->get()
            ->keyBy('id');

        $carLabels = $averageRatings->map(function ($item) use ($carDetails) {
            $car = $carDetails->get($item->car_id);
            return $car ? "{$car->brand} {$car->model}" : 'Unknown Car';
        });

        $ratings = $averageRatings->map(function ($item) {
            return round($item->average_rating, 2);
        });

        return view('admin.charts.average_reviews', compact('carLabels', 'ratings'));
    }

    public function revenuesChart()
    {
        $startDate = now()->addHour(2)->startOfYear();
        $endDate = now()->addHour(2);

        $monthlyRevenues = Payment::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(amount) as total_revenue')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('type', ['rental', 'penalty'])
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT),
                    'revenue' => round($item->total_revenue, 2),
                ];
            });

        $months = $monthlyRevenues->pluck('month');
        $revenues = $monthlyRevenues->pluck('revenue');

        return view('admin.charts.revenues', compact('months', 'revenues'));
    }

    public function calendar()
    {
        return view('admin.calendar', [
            'cars' => Car::all(),
            'reservations' => Reservation::where('status', 'confirmed')->get(),
        ]);
    }
}
