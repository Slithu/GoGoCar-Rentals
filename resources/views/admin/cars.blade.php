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
            <h1>Cars Availability</h1>
        </div>
    </div><br><br>
    <div class="row mb-4 d-flex justify-content-center">
        <div class="col-6">
            <form method="GET" action="{{ route('admin.cars') }}">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Search by brand and model" value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        @foreach ($cars as $car)
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title">Car ID: {{ $car->id }}</h5>
                        <h5 class="card-title"><strong>{{ $car->brand }} {{ $car->model }}</strong></h5>
                        <p class="card-text">
                            <strong>Availability:</strong>
                            @if ($car->availability->available)
                                <span class="text-success">Available</span>
                            @else
                                <span class="text-danger">Not Available</span>
                                <br>
                                <strong>Rentals:</strong>
                                <br>
                                @if ($car->reservations)
                                    @foreach ($car->reservations as $reservation)
                                        {{ $reservation->start_date }} - {{ $reservation->end_date }}<br>
                                    @endforeach
                                @else
                                    No reservations found.
                                @endif
                            @endif
                        </p>
                        <div class="row">
                            <div class="col">
                                <p class="card-text"><strong>Car body:</strong> {{ $car->car_body }}</p>
                            </div>
                            <div class="col">
                                <p class="card-text"><strong>Engine type:</strong> {{ $car->engine_type }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <p class="card-text"><strong>Transmission:</strong> {{ $car->transmission }}</p>
                            </div>
                            <div class="col">
                                <p class="card-text"><strong>Engine power:</strong> {{ $car->engine_power }} HP</p>
                            </div>
                        </div>
                        <p class="card-text text-center"><strong>Price:</strong> {{ $car->price }} PLN</p>
                    </div>
                    @can('isAdmin')
                        <div class="card-footer text-center">
                            <a href="{{ route('cars.show', $car->id) }}" class="btn btn-success btn-sm">Show</a>
                            <a href="{{ route('cars.edit', $car->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('cars.destroy', $car->id) }}" class="d-inline delete-form" id="delete-form-{{ $car->id }}">
                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $car->id }})">Delete</button>
                            </form>
                        </div>
                    @endcan
                </div>
            </div>
        @endforeach
    </div>
</div>

<div id="customModal" class="custom-modal">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h5 class="custom-modal-title">Confirm Deletion</h5>
            <span class="custom-modal-close" onclick="closeModal()">&times;</span>
        </div>
        <div class="custom-modal-body">
            <p>Are you sure you want to delete this car?</p>
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

    function confirmDelete(carId) {
        deleteFormId = 'delete-form-' + carId;
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
