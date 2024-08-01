@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
        </div>
        <div class="col-10">
            <h1>Cars Reviews</h1>
        </div>
    </div>
    <br>
    <div class="row mb-4 d-flex justify-content-center">
        <div class="col-6">
            <form method="GET" action="{{ route('reviews.index') }}">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Search by user and car" value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </form>
        </div>
    </div>
    <table class="table table-striped table-hover">
        <thead>
            <tr>
            <th scope="col">ID</th>
            <th scope="col">Rental ID</th>
            <th scope="col">User ID</th>
            <th scope="col">User Name</th>
            <th scope="col">User Surname</th>
            <th scope="col">Car ID</th>
            <th scope="col">Car Name</th>
            <th scope="col">Comfort</th>
            <th scope="col">Driving Experience</th>
            <th scope="col">Fuel Efficiency</th>
            <th scope="col">Safety</th>
            <th scope="col">Overall</th>
            <th scope="col">Comment</th>
            <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reviews as $review)
                <tr>
                    <th scope="row">{{$review->id}}</th>
                    <td>{{$review->reservation_id}}</td>
                    <td>{{$review->user->id}}</td>
                    <td>{{$review->user->name}}</td>
                    <td>{{$review->user->surname}}</td>
                    <td>{{$review->car->id}}</td>
                    <td>{{$review->car->brand}} {{$review->car->model}}</td>
                    <td>{{$review->comfort_rating}}</td>
                    <td>{{$review->driving_experience_rating}}</td>
                    <td>{{$review->fuel_efficiency_rating}}</td>
                    <td>{{$review->safety_rating}}</td>
                    <td>{{$review->overall_rating}}</td>
                    <td>{{$review->comment}}</td>
                    <td>
                        <a href="{{ route('reviews.show', $review->id) }}">
                            <button class="btn btn-success btn-sm">Show</button>
                        </a>
                        <a href="{{ route('reviews.edit', $review->id) }}">
                            <button class="btn btn-warning btn-sm">Edit</button>
                        </a>
                        <a href="{{ route('reviews.destroy', $review->id) }}">
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="position-fixed bottom-0 start-50 translate-middle-x">
        {{$reviews->links()}}
    </div>
</div>
@endsection
