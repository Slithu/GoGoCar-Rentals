@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center bg-primary text-white">Creating A New Rental</div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('reservations.store') }}" id="reservation-form">
                        @csrf

                        <div class="row mb-3">
                            <label for="user_id" class="col-md-4 col-form-label text-md-end">User</label>

                            <div class="col-md-6">
                                <input type="hidden" id="user_id" name="user_id" value="{{ auth()->user()->id }}">
                                <input type="text" class="form-control" value="{{ auth()->user()->name }} {{ auth()->user()->surname }}" disabled>

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
                                @foreach($cars as $car)
                                    @if($car->id == $selectedCarId)
                                        <input type="hidden" id="car_id" name="car_id" value="{{ $car->id }}">
                                        <input type="hidden" id="carPrice" value="{{ $car->price }}">
                                        <input type="text" class="form-control" value="{{ $car->brand }} {{ $car->model }}" disabled>
                                    @endif
                                @endforeach

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
                                <input id="start_date" type="text" class="form-control @error('start_date') is-invalid @enderror" name="start_date" value="{{ old('start_date') }}" required autocomplete="start_date">

                                @error('start_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="end_date" class="col-md-4 col-form-label text-md-end">Date of Return</label>

                            <div class="col-md-6">
                                <input id="end_date" type="text" class="form-control @error('end_date') is-invalid @enderror" name="end_date" value="{{ old('end_date') }}" required autocomplete="end_date">

                                @error('end_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="total_price" class="col-md-4 col-form-label text-md-end">Total price</label>

                            <div class="col-md-6">
                                <input id="total_price" type="number" step="0.01" min="0" class="form-control @error('total_price') is-invalid @enderror" name="total_price" value="{{ old('total_price') }}" required autocomplete="total_price" readonly>

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
                                <select class="form-control" id="status" name="status" required>
                                    <option value="pending">Pending</option>
                                    {{--
                                    <option value="confirmed">Confirmed</option>
                                    --}}
                                    {{--
                                    <option value="cancelled">Cancelled</option>
                                    --}}
                                </select>

                                @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-5">
                                <button type="submit" class="btn btn-primary">Go to Payment</button>
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
        flatpickr("#start_date", {
            enableTime: true,
            minuteIncrement: 30,
            dateFormat: "Y-m-d H:i",
            time_24hr: true
        });

        flatpickr("#end_date", {
            enableTime: true,
            minuteIncrement: 30,
            dateFormat: "Y-m-d H:i",
            time_24hr: true
        });

        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const totalPriceInput = document.getElementById('total_price');
        const carPrice = parseFloat(document.getElementById('carPrice').value);

        function calculateTotalPrice() {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);
            if (startDate && endDate && startDate < endDate) {
                const diffTime = endDate - startDate;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                const totalPrice = diffDays * carPrice;
                totalPriceInput.value = totalPrice.toFixed(2);
            } else {
                totalPriceInput.value = 0;
            }
        }

        startDateInput.addEventListener('change', calculateTotalPrice);
        endDateInput.addEventListener('change', calculateTotalPrice);
    });
</script>
@endsection
