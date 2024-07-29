@extends('layouts.app')

@section('content')
    <div class="container mt-3">
        <h1 class="text-center">Age Distribution Chart</h1>

        <div class="row mt-4">
            <div class="col-md-12">
                <canvas id="ageChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('ageChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($ageLabels),
                    datasets: [{
                        label: 'Number of Users',
                        data: @json($ageData),
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
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
                                    return `Number of Users: ${tooltipItem.raw}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Age Range'
                            },
                            ticks: {
                                autoSkip: false
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Number of Users'
                            },
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@endsection
