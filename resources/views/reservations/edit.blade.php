@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center bg-primary text-white"><p class="mb-0">Editing A Rental</p></div>

                <div class="card-body">
                    <form method="POST" action="{{ route('reservations.update', $reservations->id) }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="user_id" class="col-md-4 col-form-label text-md-end">User</label>
                            <div class="col-md-6">
                                @if(Auth::user()->role === 'admin')
                                <select id="user_id" class="form-control @error('user_id') is-invalid @enderror" name="user_id" required>
                                    <option value="{{ $reservations->user->id }}">{{ $reservations->user->name }} {{ $reservations->user->surname }}</option>
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} {{ $user->surname }}</option>
                                    @endforeach
                                </select>
                                @else
                                <input type="text" class="form-control" value="{{ $reservations->user->name }} {{ $reservations->user->surname }}" disabled>
                                <input type="hidden" name="user_id" value="{{ $reservations->user->id }}">
                                @endif

                                @error('user_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="car_id" class="col-md-4 col-form-label text-md-end">Car</label>
                            <div class="col-md-6">
                                @if(Auth::user()->role === 'admin')
                                <select id="car_id" class="form-control @error('car_id') is-invalid @enderror" name="car_id" required>
                                    <option value="{{ $reservations->car->id }}">{{ $reservations->car->brand }} {{ $reservations->car->model }}</option>
                                    @foreach($cars as $car)
                                    <option value="{{ $car->id }}">{{ $car->brand }} {{ $car->model }}</option>
                                    @endforeach
                                </select>
                                @else
                                <input type="text" class="form-control" value="{{ $reservations->car->brand }} {{ $reservations->car->model }}" disabled>
                                <input type="hidden" name="car_id" value="{{ $reservations->car->id }}">
                                @endif

                                @error('car_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="start_date" class="col-md-4 col-form-label text-md-end">Rental Date</label>
                            <div class="col-md-6">
                                @if(Auth::user()->role === 'admin')
                                <input id="start_date" type="datetime-local" class="form-control @error('start_date') is-invalid @enderror" name="start_date" value="{{ old('start_date', \Carbon\Carbon::parse($reservations->start_date)->format('Y-m-d\TH:i')) }}" required>
                                @else
                                <input id="start_date" type="datetime-local" class="form-control @error('start_date') is-invalid @enderror" name="start_date" value="{{ old('start_date', \Carbon\Carbon::parse($reservations->start_date)->format('Y-m-d\TH:i')) }}" required disabled>
                                <input type="hidden" name="start_date" value="{{ $reservations->start_date }}">
                                @endif

                                @error('start_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="end_date" class="col-md-4 col-form-label text-md-end">Return Date</label>
                            <div class="col-md-6">
                                @if(Auth::user()->role === 'admin')
                                <input id="end_date" type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" name="end_date" value="{{ old('end_date', \Carbon\Carbon::parse($reservations->end_date)->format('Y-m-d\TH:i')) }}" required>
                                @else
                                <input id="end_date" type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" name="end_date" value="{{ old('end_date', \Carbon\Carbon::parse($reservations->end_date)->format('Y-m-d\TH:i')) }}" required disabled>
                                <input type="hidden" name="end_date" value="{{ $reservations->end_date }}">
                                @endif

                                @error('end_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="total_price" class="col-md-4 col-form-label text-md-end">Total Price</label>
                            <div class="col-md-6">
                                <input id="total_price" type="number" step="0.01" min="0" class="form-control @error('total_price') is-invalid @enderror" name="total_price" value="{{ old('total_price', $reservations->total_price) }}" required readonly>
                                <input type="hidden" id="carPrice" value="{{ $reservations->car->price }}">

                                @error('total_price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="status" class="col-md-4 col-form-label text-md-end">Status</label>
                            <div class="col-md-6">
                                @can('isUser')
                                    <select class="form-control" id="status" name="status" required>
                                        @if ($reservations->status === 'cancelled')
                                            <option value="cancelled" @if(old('status', $reservations->status) === 'cancelled') selected @endif>Cancelled</option>
                                        @elseif ($reservations->status === 'confirmed')
                                            <option value="confirmed" @if(old('status', $reservations->status) === 'confirmed') selected @endif>Confirmed</option>
                                            <option value="cancelled" @if(old('status', $reservations->status) === 'cancelled') selected @endif>Cancelled</option>
                                        @else
                                            <option value="pending" @if(old('status', $reservations->status) === 'pending') selected @endif>Pending</option>
                                            <option value="cancelled" @if(old('status', $reservations->status) === 'cancelled') selected @endif>Cancelled</option>
                                        @endif
                                    </select>
                                @endcan
                                @can('isAdmin')
                                    <select class="form-control" id="status" name="status" required>
                                            <option value="pending" @if(old('status', $reservations->status) === 'pending') selected @endif>Pending</option>
                                            <option value="confirmed" @if(old('status', $reservations->status) === 'confirmed') selected @endif>Confirmed</option>
                                        <option value="cancelled" @if(old('status', $reservations->status) === 'cancelled') selected @endif>Cancelled</option>
                                    </select>
                                @endcan

                                @error('status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-5">
                                <button type="submit" class="btn btn-primary">Save</button>
                                @can('isAdmin')
                                    <a href="{{ route('reservations.index') }}" class="btn btn-secondary">Cancel</a>
                                @endcan
                                @can('isUser')
                                    <a href="{{ route('reservations.session') }}" class="btn btn-secondary">Cancel</a>
                                @endcan
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const totalPriceInput = document.getElementById('total_price');
        const carPricePerDay = parseFloat(document.getElementById('carPrice').value);

        function calculateTotalPrice() {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);

            if (startDate && endDate && startDate < endDate) {
                const diffTime = endDate.getTime() - startDate.getTime();
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                const totalPrice = diffDays * carPricePerDay;

                totalPriceInput.value = totalPrice.toFixed(2);
            } else {
                totalPriceInput.value = "0.00";
            }
        }

        calculateTotalPrice();

        startDateInput.addEventListener('change', calculateTotalPrice);
        endDateInput.addEventListener('change', calculateTotalPrice);
    });
</script>
@endsection
