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

class ReviewController extends Controller
{
    public function index() : View
    {
        return view("reviews.index", [
            'reviews' => Review::paginate(4),
            'users' => User::all(),
            'cars' => Car::all(),
            'reservations' => Reservation::all(),
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
        $review = new Review($request->validated());
        $review->save();

        return redirect(route('reviews.user'))->with('status', 'Review stored!');
    }
}
