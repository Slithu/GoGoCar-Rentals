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
                        <h5 class="text-center mb-4"><strong>Recommendation Algorithm</strong></h5>
                        <div class="text-center">
                            <!-- Aktualny aktywny algorytm -->
                            <p><strong>Actual Active Algorithm: {{ $activeAlgorithm ? $activeAlgorithm->algorithm_name : 'naive_bayes' }}</strong></p>

                            <!-- Lista algorytmów do wyboru -->
                            <form action="{{ route('admin.update.recommendation') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="algorithm" class="form-label"><strong>Select Active Algorithm</strong></label>
                                    <select name="active_algorithm" id="algorithm" class="form-select w-50 mx-auto text-center">
                                        <option value="naive_bayes" {{ $activeAlgorithm && $activeAlgorithm->algorithm_name == 'naive_bayes' ? 'selected' : '' }}>Naive Bayes</option>
                                        <option value="knn" {{ $activeAlgorithm && $activeAlgorithm->algorithm_name == 'knn' ? 'selected' : '' }}>KNN</option>
                                        <option value="mlp" {{ $activeAlgorithm && $activeAlgorithm->algorithm_name == 'mlp' ? 'selected' : '' }}>MLP</option>
                                        <option value="decision_tree" {{ $activeAlgorithm && $activeAlgorithm->algorithm_name == 'decision_tree' ? 'selected' : '' }}>Decision Tree</option>
                                    </select>
                                </div>

                                <!-- Parametry dla różnych algorytmów -->
                                <div class="form-group mt-3" id="k-param-group" style="display: none;">
                                    <label for="knn_k" class="form-label"><strong>Parameter k (for KNN)</strong></label>
                                    <input type="number" name="knn_k" id="knn_k" class="form-control w-25 mx-auto text-center" min="1" max="100" placeholder="3" value="{{ $activeAlgorithm && $activeAlgorithm->knn_k ? $activeAlgorithm->knn_k : 3 }}">
                                </div>

                                <div class="form-group mt-3" id="mlp-param-group" style="display: none;">
                                    <label for="mlp_hidden_layer_1" class="form-label"><strong>First Hidden Layer Neurons</strong></label>
                                    <input type="number" name="mlp_hidden_layer_1" id="mlp_hidden_layer_1" class="form-control w-25 mx-auto text-center" min="1" max="100" placeholder="8" value="{{ $activeAlgorithm && $activeAlgorithm->mlp_hidden_layer_1 ? $activeAlgorithm->mlp_hidden_layer_1 : 8 }}">

                                    <label for="mlp_hidden_layer_2" class="form-label mt-2"><strong>Second Hidden Layer Neurons</strong></label>
                                    <input type="number" name="mlp_hidden_layer_2" id="mlp_hidden_layer_2" class="form-control w-25 mx-auto text-center" min="1" max="100" placeholder="8" value="{{ $activeAlgorithm && $activeAlgorithm->mlp_hidden_layer_2 ? $activeAlgorithm->mlp_hidden_layer_2 : 8 }}">

                                    <label for="mlp_iterations" class="form-label mt-2"><strong>Number of Iterations</strong></label>
                                    <input type="number" name="mlp_iterations" id="mlp_iterations" class="form-control w-25 mx-auto text-center" min="100" max="100000" placeholder="1000" value="{{ $activeAlgorithm && $activeAlgorithm->mlp_iterations ? $activeAlgorithm->mlp_iterations : 1000 }}">
                                </div>

                                <div class="form-group mt-3" id="decision-tree-param-group" style="display: none;">
                                    <label for="decision_tree_depth" class="form-label"><strong>Tree Depth (for Decision Tree)</strong></label>
                                    <input type="number" name="decision_tree_depth" id="decision_tree_depth" class="form-control w-25 mx-auto text-center" min="1" max="50" placeholder="5" value="{{ $activeAlgorithm && $activeAlgorithm->decision_tree_depth ? $activeAlgorithm->decision_tree_depth : 5 }}">
                                </div>

                                <button type="submit" class="btn btn-primary mt-3">Save</button>
                            </form><br>

                            <!-- Wyświetlanie dodatkowych informacji o algorytmie -->
                            <section class="mb-5">
                                <h5 class="text-center mb-4"><strong>Recommendation Algorithm Details</strong></h5>

                                <div class="accordion" id="algorithmDetailsAccordion">
                                    @foreach($algorithms as $algorithm)
                                        @php
                                            $activationHistory = $algorithm->activationHistory; // Paginowana historia
                                            $totalHistory = $algorithm->totalActivationHistory; // Całkowita liczba elementów
                                            $totalPages = $algorithm->totalPages; // Liczba stron
                                            $accordionOpen = request()->get('accordion_open', null); // Parametr otwartej sekcji
                                        @endphp

                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading-{{ $algorithm->id }}">
                                                <button class="accordion-button collapsed text-white"
                                                        type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapse-{{ $algorithm->id }}"
                                                        aria-expanded="false"
                                                        aria-controls="collapse-{{ $algorithm->id }}"
                                                        style="background-color: #0d6efd"
                                                        data-page="{{ request()->get('page', 1) }}"
                                                        @if($accordionOpen == $algorithm->id) aria-expanded="true" @endif>
                                                    {{ ucfirst($algorithm->algorithm_name) }}
                                                </button>
                                            </h2>
                                            <div id="collapse-{{ $algorithm->id }}"
                                                 class="accordion-collapse collapse @if($accordionOpen == $algorithm->id) show @endif"
                                                 aria-labelledby="heading-{{ $algorithm->id }}"
                                                 data-bs-parent="#algorithmDetailsAccordion">
                                                <div class="accordion-body">
                                                    <p><strong>Activation Count:</strong> {{ $algorithm->activation_count }}</p>
                                                    <p><strong>Recommend Cars Reservations Count:</strong> {{ $algorithm->reservations_count ?? 0 }}</p>

                                                    <h6>Activation and Deactivation History:</h6>
                                                    @if($activationHistory->isNotEmpty())
                                                        <ul class="list-unstyled">
                                                            @foreach($activationHistory as $history)
                                                                <li>
                                                                    <strong>Activated:</strong> {{ $history['activation'] }}
                                                                    <br><strong>Deactivated:</strong> {{ $history['deactivation'] }}
                                                                </li>
                                                                <hr>
                                                            @endforeach
                                                        </ul>

                                                        <!-- Paginacja -->
                                                        <div class="d-flex justify-content-center">
                                                            <div>
                                                                @if(request()->get('page', 1) > 1)
                                                                    <a href="{{ route('admin.index', ['page' => request()->get('page', 1) - 1, 'accordion_open' => $algorithm->id]) }}" class="btn btn-primary">Previous</a>
                                                                @endif

                                                                @if(request()->get('page', 1) < $totalPages)
                                                                    <a href="{{ route('admin.index', ['page' => request()->get('page', 1) + 1, 'accordion_open' => $algorithm->id]) }}" class="btn btn-primary">Next</a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @else
                                                        <p>No activation history available.</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </section>
                        </div>

                        <script>
                            function toggleKParamVisibility() {
                                const algorithmSelect = document.getElementById('algorithm');
                                const kParamGroup = document.getElementById('k-param-group');
                                const mlpParamGroup = document.getElementById('mlp-param-group');
                                const decisionTreeParamGroup = document.getElementById('decision-tree-param-group');

                                kParamGroup.style.display = algorithmSelect.value === 'knn' ? 'block' : 'none';
                                mlpParamGroup.style.display = algorithmSelect.value === 'mlp' ? 'block' : 'none';
                                decisionTreeParamGroup.style.display = algorithmSelect.value === 'decision_tree' ? 'block' : 'none';
                            }

                            document.addEventListener('DOMContentLoaded', () => {
                                const algorithmSelect = document.getElementById('algorithm');
                                algorithmSelect.addEventListener('change', toggleKParamVisibility);
                                toggleKParamVisibility();
                            });
                        </script>
                    </section>

                    <hr>
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
