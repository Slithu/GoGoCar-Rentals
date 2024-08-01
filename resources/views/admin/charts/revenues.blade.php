@extends('layouts.app')

@section('content')
    <div class="container mt-3">
        <h1 class="text-center">Monthly Revenue Chart (From Payments)</h1>

        <div class="row mt-4">
            <div class="col-md-12 mb-4">
                <canvas id="revenueChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctxRevenue, {
                type: 'bar',
                data: {
                    labels: @json($months),
                    datasets: [{
                        label: 'Monthly Revenue (PLN)',
                        data: @json($revenues),
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function (tooltipItem) {
                                    return `Revenue: ${tooltipItem.raw.toFixed(2)} PLN`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Month'
                            },
                            ticks: {
                                autoSkip: false,
                                maxRotation: 90,
                                minRotation: 45
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Revenue (PLN)'
                            },
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@endsection
