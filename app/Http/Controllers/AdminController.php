<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Car;
use App\Models\User;
use App\Models\Reservation;
use App\Models\Review;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index() : View
    {
        return view("admin.index");
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

    public function cars() : View
    {
        Artisan::call('update:car-availability');

        return view("admin.cars", [
            'cars' => Car::all(),
            'reservations' => Reservation::all()
        ]);
    }

    public function usersChart()
    {
        $startDate = now()->startOfYear();
        $endDate = now();

        $monthlyUserCounts = User::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
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

        foreach (User::all() as $user) {
            // Konwertuj string na obiekt Carbon
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
            'Male'   => User::where('sex', 'male')->count(),
            'Female' => User::where('sex', 'female')->count(),
        ];

        $genderLabels = array_keys($genderCounts);
        $genderData = array_values($genderCounts);

        return view('admin.charts.users.gender', compact('genderLabels', 'genderData'));
    }

    public function carsChart()
    {
        $startDate = now()->startOfYear();
        $endDate = now();

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
        $startDate = now()->startOfYear();
        $endDate = now();

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
        $startDate = now()->startOfYear();
        $endDate = now();

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
}
