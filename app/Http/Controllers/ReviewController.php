<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\User;
use App\Models\Car;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreReviewRequest;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use App\Models\AdminNotification;
use App\Models\UserNotification;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReviewMail;

class ReviewController extends Controller
{
    public function index(Request $request) : View
    {
        $search = $request->input('search');

        $query = Review::query();

        if ($search) {
            $searchTerms = explode(' ', $search);

            $query->where(function ($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $q->where(function ($query) use ($term) {
                        $query->whereHas('user', function ($subQuery) use ($term) {
                            $subQuery->where('name', 'like', "%{$term}%")
                                    ->orWhere('surname', 'like', "%{$term}%")
                                    ->orWhere('email', 'like', "%{$term}%");
                        })
                        ->orWhereHas('car', function ($subQuery) use ($term) {
                            $subQuery->where('brand', 'like', "%{$term}%")
                                    ->orWhere('model', 'like', "%{$term}%");
                        });
                    });
                }
            });
        }

        $reviews = $query->paginate(4);

        $users = User::all();
        $cars = Car::all();
        $reservations = Reservation::all();

        return view('reviews.index', [
            'reviews' => $reviews,
            'users' => $users,
            'cars' => $cars,
            'reservations' => $reservations,
        ]);
    }

    public function show(Review $review) : View
    {
        return view("reviews.show", [
            'review' => $review,
            'users' => User::all(),
            'cars' => Car::all(),
            'reservations' => Reservation::all(),
        ]);
    }

    public function edit(Review $review) : View
    {
        return view('reviews.edit', [
            'review' => $review,
            'users' => User::all(),
            'cars' => Car::all(),
            'reservations' => Reservation::all(),
        ]);
    }

    public function update(StoreReviewRequest $request, Review $review) : RedirectResponse
    {
        $review->fill($request->validated());
        $review->save();

        return redirect(route('reviews.index'))->with('status', 'Review updated!');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return redirect(route('reviews.index'))->with('status', 'Review deleted!');
    }

    public function userReviews()
    {
        $user_id = Auth::id();
        $reviews = Review::where('user_id', $user_id)->orderBy('created_at', 'desc')->get();

        return view('reviews.user', [
            'reviews' => $reviews,
        ]);
    }

    public function storeReview(StoreReviewRequest $request) : RedirectResponse
    {
        $user = Auth::user();
        $review = new Review($request->validated());
        $review->save();

        UserNotification::create([
            'user_id' => $user->id,
            'title' => "New car review completed!",
            'message' => "{$review->user->name} {$review->user->surname}\nCar: {$review->car->brand} {$review->car->model}\nComfort: {$review->comfort_rating}\nDriving Experience: {$review->driving_experience_rating}\nFuel Efficiency: {$review->fuel_efficiency_rating}\nSafety Rating: {$review->safety_rating}\nComment: {$review->comment}",
            'type' => 'review',
            'status' => 'unread',
        ]);

        AdminNotification::create([
            'user_id' => 1,
            'title' => "New car review completed!",
            'message' => "User ID: {$review->user_id}\nUser: {$review->user->name} {$review->user->surname}\nCar ID: {$review->car_id}\nCar: {$review->car->brand} {$review->car->model}\nComfort: {$review->comfort_rating}\nDriving Experience: {$review->driving_experience_rating}\nFuel Efficiency: {$review->fuel_efficiency_rating}\nSafety Rating: {$review->safety_rating}\nComment: {$review->comment}",
            'type' => 'review',
            'status' => 'unread',
        ]);

        $user = User::findOrFail($review->user_id);
        Mail::to($user->email)->send(new ReviewMail($review));

        return redirect(route('reviews.user'))->with('status', 'Review stored!');
    }
}
