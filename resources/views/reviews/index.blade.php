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
                        <form action="{{ route('reviews.destroy', $review->id) }}" class="d-inline delete-form" id="delete-form-{{ $review->id }}">
                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $review->id }})">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="position-fixed bottom-0 start-50 translate-middle-x">
        {{$reviews->links()}}
    </div>
</div>

<div id="customModal" class="custom-modal">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h5 class="custom-modal-title">Confirm Deletion</h5>
            <span class="custom-modal-close" onclick="closeModal()">&times;</span>
        </div>
        <div class="custom-modal-body">
            <p>Are you sure you want to delete this review?</p>
        </div>
        <div class="custom-modal-footer gap-3">
            <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
        </div>
    </div>
</div>

<style>
    .custom-modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.4);
    }

    .custom-modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 40%;
        max-width: 500px;
        border-radius: 8px;
    }

    .custom-modal-header, .custom-modal-footer {
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .custom-modal-title {
        margin: 0;
        font-size: 20px;
        font-weight: bold;
    }

    .custom-modal-close {
        color: #aaa;
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .custom-modal-close:hover,
    .custom-modal-close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    .custom-modal-body {
        text-align: center;
        margin: 20px 0;
    }
</style>

<script>
    let deleteFormId = null;

    function confirmDelete(reviewId) {
        deleteFormId = 'delete-form-' + reviewId;
        document.getElementById('customModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('customModal').style.display = 'none';
    }

    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (deleteFormId) {
            document.getElementById(deleteFormId).submit();
        }
        closeModal();
    });
</script>

@endsection
