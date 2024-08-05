@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="card shadow-lg rounded-3">
                <div class="card-header bg-primary text-white text-center rounded-top">
                    <h5 class="mb-0">Admin Panel</h5>
                </div>

                <div class="card-body">
                    <section class="mb-5">
                        <h5 class="text-center mb-4"><strong>Reports</strong></h5>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="card border-primary">
                                    <div class="card-body text-center">
                                        <a href="{{ route('admin.reports.users') }}" class="btn btn-outline-primary btn-lg">
                                            <i class="fas fa-users"></i> Download Users Report
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-primary">
                                    <div class="card-body text-center">
                                        <a href="{{ route('admin.reports.cars') }}" class="btn btn-outline-primary btn-lg">
                                            <i class="fas fa-car"></i> Download Cars Report
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-primary">
                                    <div class="card-body text-center">
                                        <a href="{{ route('admin.reports.rentals') }}" class="btn btn-outline-primary btn-lg">
                                            <i class="fas fa-calendar-check"></i> Download All Rentals Report
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-primary">
                                    <div class="card-body text-center">
                                        <a href="{{ route('admin.reports.daily_rentals') }}" class="btn btn-outline-primary btn-lg">
                                            <i class="fas fa-calendar-day"></i> Download Daily Rentals Report
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-primary">
                                    <div class="card-body text-center">
                                        <a href="{{ route('admin.reports.weekly_rentals') }}" class="btn btn-outline-primary btn-lg">
                                            <i class="fas fa-calendar-week"></i> Download Weekly Rentals Report
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-primary">
                                    <div class="card-body text-center">
                                        <a href="{{ route('admin.reports.monthly_rentals') }}" class="btn btn-outline-primary btn-lg">
                                            <i class="fas fa-calendar-month"></i> Download Monthly Rentals Report
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card border-primary">
                                    <div class="card-body text-center">
                                        <a href="{{ route('admin.reports.reviews') }}" class="btn btn-outline-primary btn-lg">
                                            <i class="fas fa-star"></i> Download Cars Reviews Report
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="mb-5">
                        <h5 class="text-center mb-4"><strong>Cars Availability</strong></h5>
                        <div class="text-center">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <a href="{{ route('admin.cars') }}" class="btn btn-outline-success btn-lg">
                                        <i class="fas fa-car"></i> View Cars Availability
                                    </a>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="mb-5">
                        <h5 class="text-center mb-4"><strong>Rentals Calendar</strong></h5>
                        <div class="text-center">
                            <div class="card border-warning">
                                <div class="card-body text-center">
                                    <a href="{{ route('admin.calendar') }}" class="btn btn-outline-warning btn-lg">
                                        <i class="fas fa-calendar-alt"></i> View Rentals Calendar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section>
                        <h5 class="text-center mb-4"><strong>Charts</strong></h5>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="card border-info">
                                    <div class="card-body text-center">
                                        <a href="{{ route('admin.charts.users.users') }}" class="btn btn-outline-info btn-lg">
                                            <i class="fas fa-chart-line"></i> Users Chart
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-info">
                                    <div class="card-body text-center">
                                        <a href="{{ route('admin.charts.users.age') }}" class="btn btn-outline-info btn-lg">
                                            <i class="fas fa-chart-pie"></i> Age Of Users
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-info">
                                    <div class="card-body text-center">
                                        <a href="{{ route('admin.charts.users.gender') }}" class="btn btn-outline-info btn-lg">
                                            <i class="fas fa-genderless"></i> Gender Of Users
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-info">
                                    <div class="card-body text-center">
                                        <a href="{{ route('admin.charts.cars') }}" class="btn btn-outline-info btn-lg">
                                            <i class="fas fa-chart-bar"></i> Cars Chart
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-info">
                                    <div class="card-body text-center">
                                        <a href="{{ route('admin.charts.rentals.rentals') }}" class="btn btn-outline-info btn-lg">
                                            <i class="fas fa-chart-area"></i> Rentals Chart
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-info">
                                    <div class="card-body text-center">
                                        <a href="{{ route('admin.charts.rentals.car_body') }}" class="btn btn-outline-info btn-lg">
                                            <i class="fas fa-car"></i> Car Body Rentals Chart
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-info">
                                    <div class="card-body text-center">
                                        <a href="{{ route('admin.charts.rentals.brands') }}" class="btn btn-outline-info btn-lg">
                                            <i class="fas fa-industry"></i> Car Brands Rentals Chart
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-info">
                                    <div class="card-body text-center">
                                        <a href="{{ route('admin.charts.rentals.average_price') }}" class="btn btn-outline-info btn-lg">
                                            <i class="fas fa-dollar-sign"></i> Average Rentals Price Chart
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-info">
                                    <div class="card-body text-center">
                                        <a href="{{ route('admin.charts.rentals.rental_duration') }}" class="btn btn-outline-info btn-lg">
                                            <i class="fas fa-hourglass-half"></i> Average Rentals Duration Chart
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-info">
                                    <div class="card-body text-center">
                                        <a href="{{ route('admin.charts.reviews') }}" class="btn btn-outline-info btn-lg">
                                            <i class="fas fa-star"></i> Reviews Chart
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-info">
                                    <div class="card-body text-center">
                                        <a href="{{ route('admin.charts.average_reviews') }}" class="btn btn-outline-info btn-lg">
                                            <i class="fas fa-star-half-alt"></i> Average Reviews Chart
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-info">
                                    <div class="card-body text-center">
                                        <a href="{{ route('admin.charts.revenues') }}" class="btn btn-outline-info btn-lg">
                                            <i class="fas fa-money-bill-wave"></i> Revenues Chart
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
