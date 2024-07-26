@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white text-center"><p class="mb-0">Your Car Reviews</p></div>

                <div class="card-body">
                    @if ($reviews->isEmpty())
                        <p class="text-center">No reviews found.</p>
                    @else
                        <div class="list-group">
                            @foreach ($reviews as $review)
                                <div class="list-group-item">
                                    <h5 class="card-title text-center"><strong>{{ $review->car->brand }} {{ $review->car->model }}</strong></h5>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-3 text-center">
                                            <p class="card-text"><strong>Comfort:</strong></p>
                                            <p class="card-text">{!! printStars($review->comfort_rating) !!}</p>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <p class="card-text"><strong>Driving Experience:</strong></p>
                                            <p class="card-text">{!! printStars($review->driving_experience_rating) !!}</p>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <p class="card-text"><strong>Fuel Efficiency:</strong></p>
                                            <p class="card-text">{!! printStars($review->fuel_efficiency_rating) !!}</p>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <p class="card-text"><strong>Safety:</strong></p>
                                            <p class="card-text">{!! printStars($review->safety_rating) !!}</p>
                                        </div>
                                    </div><br>
                                    <p class="card-text text-center mt-2"><strong>Comment:</strong> {{ $review->comment ?: 'No comment' }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@php
    function printStars($rating) {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rating) {
                $stars .= '★';
            } else {
                $stars .= '☆';
            }
        }
        return $stars;
    }
@endphp

<style>
    .card-text {
        font-size: 1.2rem;
    }

    .card-title {
        font-size: 1.5rem;
    }
</style>
