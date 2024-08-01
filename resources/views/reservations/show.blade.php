@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center bg-primary text-white"><p class="mb-0">Show Reservation</p></div>

                <div class="card-body">
                        <div class="row mb-3">
                            <label for="user_id" class="col-md-4 col-form-label text-md-end">User</label>
                            <div class="col-md-6">
                                <select id="user_id" class="form-control" name="user_id" disabled>
                                        <option value="{{ $reservations->user->id }}">{{ $reservations->user->name }} {{ $reservations->user->surname }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="car_id" class="col-md-4 col-form-label text-md-end">Car</label>
                            <div class="col-md-6">
                                <select id="car_id" class="form-control" name="car_id" disabled>
                                    @foreach($cars as $car)
                                            <option value="{{ $reservations->car->id }}">{{ $reservations->car->brand }} {{ $reservations->car->model }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="start_date" class="col-md-4 col-form-label text-md-end">Rental Date</label>
                            <div class="col-md-6">
                                <input id="start_date" type="datetime-local" class="form-control" name="start_date" value="{{ $reservations->start_date }}" disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="end_date" class="col-md-4 col-form-label text-md-end">Date of Return</label>
                            <div class="col-md-6">
                                <input id="end_date" type="datetime-local" class="form-control" name="end_date" value="{{ $reservations->end_date }}" disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="total_price" class="col-md-4 col-form-label text-md-end">Total price</label>
                            <div class="col-md-6">
                                <input id="total_price" type="number" step="0.01" min="0" class="form-control" name="total_price" value="{{ $reservations->total_price }}" disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="status" class="col-md-4 col-form-label text-md-end">Status</label>
                            <div class="col-md-6">
                                <select class="form-control" id="status" name="status" disabled>
                                    <option value="confirmed" <?php if ($reservations->status === 'pending') echo 'selected'; ?>>Pending</option>
                                    <option value="confirmed" <?php if ($reservations->status === 'confirmed') echo 'selected'; ?>>Confirmed</option>
                                    <option value="cancelled" <?php if ($reservations->status === 'cancelled') echo 'selected'; ?>>Cancelled</option>
                                </select>
                            </div>
                        </div>
                        @can('isAdmin')
                            <a class="nav-link" href="{{ route('reservations.index') }}" style="text-align:center">
                                <button class="btn btn-sm btn-secondary">Return to Reservations</button>
                            </a>
                        @endcan
                        @can('isUser')
                            <a class="nav-link" href="{{ route('reservations.session') }}" style="text-align:center">
                                <button class="btn btn-sm btn-secondary">Return to Reservations</button>
                            </a>
                        @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
