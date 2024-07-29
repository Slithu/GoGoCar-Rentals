@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h5 class="card-title mb-0">Admin Panel</h5>
                </div>
                <div class="card-header text-center">
                    <h5 class="card-title mb-0"><strong>Reports</strong></h5>
                </div>
                <div class="card-body text-center">
                    <div class="container">
                        <div class="row">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <a href="{{ route('admin.reports.users') }}" class="btn btn-primary btn-block">
                                    Download Users Report
                                </a>
                            </li>
                            <li class="list-group-item">
                                <a href="{{ route('admin.reports.cars') }}" class="btn btn-primary btn-block">
                                    Download Cars Report
                                </a>
                            </li>
                            <li class="list-group-item">
                                <div class="container">
                                    <div class="row">
                                        <div class="col">
                                            <a href="{{ route('admin.reports.rentals') }}" class="btn btn-primary btn-block">
                                                Download All Rentals Report
                                            </a>
                                        </div>
                                        <div class="col">
                                            <a href="{{ route('admin.reports.daily_rentals') }}" class="btn btn-primary btn-block">
                                                Download Daily Rentals Report
                                            </a>
                                        </div>
                                        <div class="w-100"></div><br>
                                        <div class="col">
                                            <a href="{{ route('admin.reports.weekly_rentals') }}" class="btn btn-primary btn-block">
                                                Download Weekly Rentals Report
                                            </a>
                                        </div>
                                        <div class="col">
                                            <a href="{{ route('admin.reports.monthly_rentals') }}" class="btn btn-primary btn-block">
                                                Download Monthly Rentals Report
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <a href="{{ route('admin.reports.reviews') }}" class="btn btn-primary btn-block">
                                    Download Cars Reviews Report
                                </a>
                            </li>
                        </ul>
                        </div>
                    </div>
                </div>

                <div class="card-header text-center">
                    <h5 class="card-title mb-0"><strong>Cars Availability</strong></h5>
                </div>
                <div class="card-body text-center">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <a href="{{ route('admin.cars') }}" class="btn btn-primary btn-block">
                                Cars Availability
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="card-header text-center">
                    <h5 class="card-title mb-0"><strong>Charts</strong></h5>
                </div>
                <div class="card-body text-center">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <a href="{{ route('admin.charts.users.users') }}" class="btn btn-primary btn-block">
                                Users Chart
                            </a>
                            <a href="{{ route('admin.charts.users.age') }}" class="btn btn-primary btn-block">
                                Age Of Users
                            </a>
                            <a href="{{ route('admin.charts.users.gender') }}" class="btn btn-primary btn-block">
                                Gender Of Users
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="{{ route('admin.charts.cars') }}" class="btn btn-primary btn-block">
                                Cars Chart
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="{{ route('admin.charts.rentals.rentals') }}" class="btn btn-primary btn-block">
                                Rentals Chart
                            </a>
                            <a href="{{ route('admin.charts.rentals.car_body') }}" class="btn btn-primary btn-block">
                                Car Body Rentals Chart
                            </a>
                            <a href="{{ route('admin.charts.rentals.brands') }}" class="btn btn-primary btn-block">
                                Car Brands Rentals Chart
                            </a>
                            <a href="{{ route('admin.charts.rentals.average_price') }}" class="btn btn-primary btn-block">
                                Average Rentals Price Chart
                            </a><br><br>
                            <a href="{{ route('admin.charts.rentals.rental_duration') }}" class="btn btn-primary btn-block">
                                Average Rentals Duration Chart
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="{{ route('admin.charts.reviews') }}" class="btn btn-primary btn-block">
                                Reviews Chart
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
