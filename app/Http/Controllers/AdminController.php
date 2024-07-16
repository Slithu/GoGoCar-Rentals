<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\File;
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
        $date = Carbon::now()->format('Y-m-d H:i:s');
        $pdf = PDF::loadView('admin.reports.rentals', compact('reservations', 'users', 'cars'));
        return $pdf->download('rentals_report.pdf');
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
}
