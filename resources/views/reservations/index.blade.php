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
        <div class="col-10">
            <h1>Rentals List</h1>
        </div>
    </div>
    <br>
    <div class="row mb-4 d-flex justify-content-center">
        <div class="col-6">
            <form method="GET" action="{{ route('reservations.index') }}">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Search by user and car" value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">User ID</th>
                    <th scope="col">User Name</th>
                    <th scope="col">User Surname</th>
                    <th scope="col">User Email</th>
                    <th scope="col">Car ID</th>
                    <th scope="col">Car</th>
                    <th scope="col">Rental Date</th>
                    <th scope="col">Date of Return</th>
                    <th scope="col">Total price (PLN)</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reservations as $reservation)
                    <tr>
                        <th scope="row">{{$reservation->id}}</th>
                        <td>{{$reservation->user->id ?? 'None' }}</td>
                        <td>{{$reservation->user->name ?? 'None' }}</td>
                        <td>{{$reservation->user->surname ?? 'None' }}</td>
                        <td>{{$reservation->user->email ?? 'None' }}</td>
                        <td>{{$reservation->car->id ?? 'None' }}</td>
                        <td>{{$reservation->car->brand ?? 'None' }} {{$reservation->car->model ?? 'None' }}</td>
                        <td>{{$reservation->start_date ?? 'None' }}</td>
                        <td>{{$reservation->end_date ?? 'None' }}</td>
                        <td>{{$reservation->total_price}}</td>
                        <td>{{$reservation->status}}</td>
                        <td>
                            @if ($reservation->carReturns->isEmpty())
                                <a href="{{ route('returns.return', $reservation->id) }}">
                                    <button class="btn btn-primary btn-sm">Take Return</button>
                                </a>
                            @endif
                            <a href="{{ route('reservations.show', $reservation->id) }}">
                                <button class="btn btn-success btn-sm">Show</button>
                            </a>
                            <a href="{{ route('reservations.edit', $reservation->id) }}">
                                <button class="btn btn-warning btn-sm">Edit</button>
                            </a>
                            <form action="{{ route('reservations.destroy', $reservation->id) }}" class="d-inline delete-form" id="delete-form-{{ $reservation->id }}">
                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $reservation->id }})">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="position-fixed bottom-0 start-50 translate-middle-x">
        {{$reservations->links()}}
    </div>
</div>

<div id="customModal" class="custom-modal">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h5 class="custom-modal-title">Confirm Deletion</h5>
            <span class="custom-modal-close" onclick="closeModal()">&times;</span>
        </div>
        <div class="custom-modal-body">
            <p>Are you sure you want to delete this rental?</p>
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

    function confirmDelete(reservationId) {
        deleteFormId = 'delete-form-' + reservationId;
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
