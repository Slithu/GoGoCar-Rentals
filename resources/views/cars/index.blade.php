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
            <h1>Cars Fleet</h1>
        </div>
        @can('isAdmin')
            <div class="col-2">
                <a class="float-right" href="{{ route('cars.create') }}">
                    <button type="button" class="btn btn-primary">Create a new Car</button>
                </a>
            </div>
        @endcan
    </div><br><br>
    <div class="row mb-4 d-flex justify-content-center">
        <div class="col-6">
            <form method="GET" action="{{ route('cars.index') }}">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Search by brand and model" value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </form>
        </div>
    </div>
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Brand</th>
                <th scope="col">Model</th>
                <th scope="col">Car Body</th>
                <th scope="col">Engine Type</th>
                <th scope="col">Transmission</th>
                <th scope="col">Engine Power</th>
                <th scope="col">Seats</th>
                <th scope="col">Doors</th>
                <th scope="col">Suitcases</th>
                <th scope="col">Price</th>
                <th scope="col">Availability</th>
                <th scope="col">Description</th>
                @can('isAdmin')
                <th scope="col">Actions</th>
                @endcan
            </tr>
        </thead>
        <tbody>
            @foreach ($cars as $car)
                <tr>
                    <th scope="row">{{$car->id}}</th>
                    <td>{{$car->brand}}</td>
                    <td>{{$car->model}}</td>
                    <td>{{$car->car_body}}</td>
                    <td>{{$car->engine_type}}</td>
                    <td>{{$car->transmission}}</td>
                    <td>{{$car->engine_power}}</td>
                    <td>{{$car->seats}}</td>
                    <td>{{$car->doors}}</td>
                    <td>{{$car->suitcases}}</td>
                    <td>{{$car->price}}</td>
                    <td>{{$car->availability->available ? 'Available' : 'Not Available' }}</td>
                    <td>{{$car->description}}</td>
                    @can('isAdmin')
                    <td>
                        <a href="{{ route('cars.show', $car->id) }}">
                            <button class="btn btn-success btn-sm">Show</button>
                        </a>
                        <a href="{{ route('cars.edit', $car->id) }}">
                            <button class="btn btn-warning btn-sm">Edit</button>
                        </a>
                        <form action="{{ route('cars.destroy', $car->id) }}" class="d-inline delete-form" id="delete-form-{{ $car->id }}">
                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $car->id }})">Delete</button>
                        </form>
                    </td>
                    @endcan
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="position-fixed bottom-0 start-50 translate-middle-x">
        {{ $cars->links() }}
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
