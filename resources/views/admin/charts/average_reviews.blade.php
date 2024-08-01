@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <h1 class="text-center">Average Car Reviews</h1>

    <div class="row mt-4">
        <div class="col-md-12">
            <canvas id="averageRatingsChart" width="400" height="200"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('averageRatingsChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($carLabels),
                datasets: [{
                    label: 'Average Review',
                    data: @json($ratings),
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
                                return `Average Review: ${tooltipItem.raw.toFixed(2)}`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Car'
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
                            text: 'Average Review'
                        },
                        beginAtZero: true,
                        suggestedMax: 5
                    }
                }
            }
        });
    });
</script>
@endsection
