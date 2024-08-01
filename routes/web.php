<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\NotificationController;
use App\Mail\PaymentMail;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/users/list', [UserController::class, 'index'])->name('users.index')->middleware('auth')->middleware('can:isAdmin');
Route::get("users/{id}/delete", [UserController::class, 'destroy'])->name('users.destroy')->middleware('auth')->middleware('can:isAdmin');
Route::post('/users/{user}', [UserController::class, 'update'])->name('users.update')->middleware('auth');
Route::get('/users/edit/{user}', [UserController::class, 'edit'])->name('users.edit')->middleware('auth');

Route::get('/profile', [UserController::class, 'profile'])->name('profile.show')->middleware('auth');
Route::post('/profile/create', [UserController::class, 'create'])->name('profile.create')->middleware('auth');
Route::post('/profile', [UserController::class, 'addProfilePhoto'])->name('profile.addProfilePhoto')->middleware('auth');
Route::get('/user/notifications', [NotificationController::class, 'user'])->name('profile.notifications')->middleware('auth')->middleware('isUser');

Route::get('/cars', [CarController::class, 'index'])->name('cars.index')->middleware('auth')->middleware('can:isAdmin');
Route::get('/cars/create', [CarController::class, 'create'])->name('cars.create')->middleware('can:isAdmin');
Route::get('/cars/{car}', [CarController::class, 'show'])->name('cars.show')->middleware('can:isAdmin');
Route::post('/cars', [CarController::class, 'store'])->name('cars.store')->middleware('can:isAdmin');
Route::post('/cars/{car}', [CarController::class, 'update'])->name('cars.update')->middleware('can:isAdmin');
Route::get('/cars/edit/{car}', [CarController::class, 'edit'])->name('cars.edit')->middleware('can:isAdmin');
Route::get('/cars/{car}/delete', [CarController::class, 'destroy'])->name('cars.destroy')->middleware('can:isAdmin');
Route::get('/cars/{car}/detail', [CarController::class, 'detail'])->name('cars.detail');

Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index')->middleware('auth');
Route::get('/reservations/create/{carId?}', [ReservationController::class, 'create'])->name('reservations.create')->middleware('auth');
Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show')->middleware('auth');
Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store')->middleware('auth');
Route::post('/reservations/{reservation}', [ReservationController::class, 'update'])->name('reservations.update')->middleware('auth');
Route::get('/reservations/edit/{reservation}', [ReservationController::class, 'edit'])->name('reservations.edit')->middleware('auth');
Route::get('/reservations/{reservation}/delete', [ReservationController::class, 'destroy'])->name('reservations.destroy')->middleware('auth');
Route::get('/session', [ReservationController::class, 'showReservations'])->name('reservations.session')->middleware('auth')->middleware('can:isUser');
Route::get('/reservations/{id}/rate', [ReservationController::class, 'rate'])->name('reservations.rate')->middleware('auth');

Route::get('/reviews/list', [ReviewController::class, 'index'])->name('reviews.index')->middleware('auth')->middleware('can:isAdmin');
Route::get('/reviews/{review}', [ReviewController::class, 'show'])->name('reviews.show')->middleware('auth')->middleware('can:isAdmin');
Route::post('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update')->middleware('auth');
Route::get('/reviews/edit/{review}', [ReviewController::class, 'edit'])->name('reviews.edit')->middleware('auth');
Route::get('/reviews/{review}/delete', [ReviewController::class, 'destroy'])->name('reviews.destroy')->middleware('can:isAdmin');
Route::get('/reviews', [ReviewController::class, 'userReviews'])->name('reviews.user')->middleware('auth')->middleware('can:isUser');
Route::post('/reviews', [ReviewController::class, 'storeReview'])->name('reviews.storeReview')->middleware('auth');

Route::get('/admin-panel', [AdminController::class, 'index'])->name('admin.index')->middleware('auth')->middleware('can:isAdmin');
Route::get('/admin/reports/users', [AdminController::class, 'generateUsersReport'])->name('admin.reports.users')->middleware('auth')->middleware('can:isAdmin');
Route::get('/admin/reports/cars', [AdminController::class, 'generateCarsReport'])->name('admin.reports.cars')->middleware('auth')->middleware('can:isAdmin');
Route::get('/admin/reports/rentals', [AdminController::class, 'generateRentalsReport'])->name('admin.reports.rentals')->middleware('auth')->middleware('can:isAdmin');
Route::get('/admin/reports/daily_rentals', [AdminController::class, 'generateDailyRentalsReport'])->name('admin.reports.daily_rentals')->middleware('auth')->middleware('can:isAdmin');
Route::get('/admin/reports/weekly_rentals', [AdminController::class, 'generateWeeklyRentalsReport'])->name('admin.reports.weekly_rentals')->middleware('auth')->middleware('can:isAdmin');
Route::get('/admin/reports/monthly_rentals', [AdminController::class, 'generateMonthlyRentalsReport'])->name('admin.reports.monthly_rentals')->middleware('auth')->middleware('can:isAdmin');
Route::get('/admin/reports/reviews', [AdminController::class, 'generateCarReviewsReport'])->name('admin.reports.reviews')->middleware('auth')->middleware('can:isAdmin');
Route::get('/admin/cars', [AdminController::class, 'cars'])->name('admin.cars')->middleware('auth')->middleware('can:isAdmin');
Route::get('/admin/charts/users', [AdminController::class, 'usersChart'])->name('admin.charts.users.users')->middleware('auth')->middleware('can:isAdmin');
Route::get('/admin/charts/users/age', [AdminController::class, 'ageChart'])->name('admin.charts.users.age')->middleware('auth')->middleware('can:isAdmin');
Route::get('/admin/charts/users/gender', [AdminController::class, 'genderChart'])->name('admin.charts.users.gender')->middleware('auth')->middleware('can:isAdmin');
Route::get('/admin/charts/cars', [AdminController::class, 'carsChart'])->name('admin.charts.cars')->middleware('auth')->middleware('can:isAdmin');
Route::get('/admin/charts/rentals', [AdminController::class, 'rentalsChart'])->name('admin.charts.rentals.rentals')->middleware('auth')->middleware('can:isAdmin');
Route::get('/admin/charts/car_body', [AdminController::class, 'carBodyChart'])->name('admin.charts.rentals.car_body')->middleware('auth')->middleware('can:isAdmin');
Route::get('/admin/charts/brands', [AdminController::class, 'brandsChart'])->name('admin.charts.rentals.brands')->middleware('auth')->middleware('can:isAdmin');
Route::get('/admin/charts/average_price', [AdminController::class, 'averagePriceChart'])->name('admin.charts.rentals.average_price')->middleware('auth')->middleware('can:isAdmin');
Route::get('/admin/charts/rental_duration', [AdminController::class, 'rentalDurationChart'])->name('admin.charts.rentals.rental_duration')->middleware('auth')->middleware('can:isAdmin');
Route::get('/admin/charts/reviews', [AdminController::class, 'reviewsChart'])->name('admin.charts.reviews')->middleware('auth')->middleware('can:isAdmin');
Route::get('/admin/charts/average_reviews', [AdminController::class, 'averageReviewsChart'])->name('admin.charts.average_reviews')->middleware('auth')->middleware('can:isAdmin');
Route::get('/admin/charts/revenues', [AdminController::class, 'revenuesChart'])->name('admin.charts.revenues')->middleware('auth')->middleware('can:isAdmin');
Route::get('/admin/notifications', [NotificationController::class, 'index'])->name('admin.notifications')->middleware('auth')->middleware('can:isAdmin');
Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead')->middleware('auth')->middleware('isUser');
Route::post('/notifications/{id}/read2', [NotificationController::class, 'markAsRead2'])->name('notifications.markAsRead2')->middleware('auth')->middleware('isAdmin');
Route::get('/notifications/{notification}/delete', [NotificationController::class, 'destroy'])->name('notifications.destroy')->middleware('auth')->middleware('isUser');
Route::get('/notifications/{notification}/delete2', [NotificationController::class, 'destroy2'])->name('notifications.destroy2')->middleware('auth')->middleware('isAdmin');

Route::get('/payments/details/{reservation}', [PaymentController::class, 'showPaymentForm'])->name('payment.payment')->middleware('auth');
Route::post('/payments/process', [PaymentController::class, 'processPayment'])->name('payment.process')->middleware('auth');
Route::get('/payments/penalty/{carReturn}', [PaymentController::class, 'showPenaltyForm'])->name('payment.penalty')->middleware('auth');
Route::post('/payments/penalty', [PaymentController::class, 'processPenalty'])->name('payment.processPenalty')->middleware('auth');
Route::get('/payments/payload/{chargeId}', [PaymentController::class, 'showPayload'])->name('payment.payload')->middleware('auth');
Route::get('/payments/list', [PaymentController::class, 'index'])->name('payment.index')->middleware('auth')->middleware('can:isAdmin');
Route::get('/payments/{payment}/delete', [PaymentController::class, 'destroy'])->name('payment.destroy')->middleware('auth')->middleware('can:isAdmin');
Route::get('/payments/user', [PaymentController::class, 'userPayments'])->name('payment.user')->middleware('auth')->middleware('can:isUser');

Route::get('/returns/form/{reservation}', [ReturnController::class, 'showReturnForm'])->name('returns.return')->middleware('auth')->middleware('can:isAdmin');
Route::post('/returns', [ReturnController::class, 'processReturn'])->name('returns.process')->middleware('auth')->middleware('can:isAdmin');
Route::get('/returns/list', [ReturnController::class, 'index'])->name('returns.index')->middleware('auth')->middleware('can:isAdmin');
Route::post('/returns/{car_return}', [ReturnController::class, 'update'])->name('returns.update')->middleware('auth')->middleware('can:isAdmin');
Route::get('/returns/edit/{car_return}', [ReturnController::class, 'edit'])->name('returns.edit')->middleware('auth')->middleware('can:isAdmin');
Route::get('/returns/{car_return}/delete', [ReturnController::class, 'destroy'])->name('returns.destroy')->middleware('auth')->middleware('can:isAdmin');
Route::get('/returns/user', [ReturnController::class, 'userReturns'])->name('returns.user_returns')->middleware('auth')->middleware('can:isUser');
