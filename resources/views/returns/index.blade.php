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
    </div>
    <div class="row">
        <div class="col-12">
            <h1>Returns List</h1>
            @if($car_returns->isEmpty())
                <br>
                <p>No returns found.</p>
            @endif
        </div>
    </div>
    <br>
    <div class="row mb-4 d-flex justify-content-center">
        <div class="col-6">
            <form method="GET" action="{{ route('returns.index') }}">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Search by user and car" value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        @foreach ($car_returns as $car_return)
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header text-center bg-primary text-white">
                        Return ID: {{$car_return->id}}
                    </div>
                    <div class="card-body">
                        <p><strong>Rental ID:</strong> {{$car_return->reservation->id ?? 'None' }}</p>
                        <p><strong>Car ID:</strong> {{$car_return->reservation->car->id ?? 'None' }}</p>
                        <p><strong>Car:</strong> {{$car_return->reservation->car->brand ?? 'None' }} {{$car_return->reservation->car->model ?? 'None' }}</p>
                        <p><strong>User ID:</strong> {{$car_return->reservation->user->id ?? 'None' }}</p>
                        <p><strong>User:</strong> {{$car_return->reservation->user->name ?? 'None' }} {{$car_return->reservation->user->surname ?? 'None' }}</p>
                        <p><strong>Rental Date:</strong> {{$car_return->reservation->start_date ?? 'None' }} --- {{$car_return->reservation->end_date ?? 'None' }}</p>
                        <p><strong>Rental Price:</strong> {{$car_return->reservation->total_price ?? 'None' }} PLN</p>
                        <p><strong>Return Date:</strong> {{$car_return->return_date ?? 'None' }}</p>
                        <p><strong>Exterior Condition:</strong> {{$car_return->exterior_condition ?? 'None'}}</p>
                        <p><strong>Interior Condition:</strong> {{$car_return->interior_condition ?? 'None'}}</p>
                        <p><strong>Exterior Damage Description:</strong> {{$car_return->exterior_damage_description ?? 'None'}}</p>
                        <p><strong>Interior Damage Description:</strong> {{$car_return->interior_condition_description ?? 'None'}}</p>
                        <p><strong>Car Parts Condition:</strong> {{$car_return->car_parts_condition ?? 'None'}}</p>
                        <p><strong>Penalty Amount:</strong> {{$car_return->penalty_amount ?? 'None'}} PLN</p>
                        <p><strong>Comments:</strong> {{$car_return->comments ?? 'None'}}</p>
                        @if ($car_return->penalty_amount > 0)
                            @if ($car_return->penalty_paid)
                                <p class="text-success"><strong>Penalty has already been paid.</strong></p>
                            @endif
                        @endif
                    </div>
                    <div class="col-md-6 offset-md-5">
                        <a href="{{ route('returns.edit', $car_return->id) }}">
                            <button class="btn btn-warning btn-sm">Edit</button>
                        </a>
                        <form action="{{ route('returns.destroy', $car_return->id) }}" class="d-inline delete-form" id="delete-form-{{ $car_return->id }}">
                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $car_return->id }})">Delete</button>
                        </form>
                    </div><br>
                </div>
            </div>
        @endforeach
    </div>
    <div class="position-fixed bottom-0 start-50 translate-middle-x">
        {{$car_returns->links()}}
    </div>
</div>

<div id="customModal" class="custom-modal">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h5 class="custom-modal-title">Confirm Deletion</h5>
            <span class="custom-modal-close" onclick="closeModal()">&times;</span>
        </div>
        <div class="custom-modal-body">
            <p>Are you sure you want to delete this car return?</p>
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
        margin: 10% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 50%;
        max-width: 600px;
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
        font-size: 18px;
        font-weight: bold;
    }

    .custom-modal-close {
        color: #aaa;
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 24px;
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

    function confirmDelete(carReturnId) {
        deleteFormId = 'delete-form-' + carReturnId;
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
