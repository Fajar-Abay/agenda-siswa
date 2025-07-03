@extends('layouts.admin')

@section('content')
<h1 class="mb-4 text-primary">Dashboard Admin</h1>

<div class="row">
    <div class="col-sm-12 col-md-4 mb-4">
        <div class="card shadow border-0 h-100">
            <div class="card-body">
                <h5 class="card-title text-primary">
                    <i class="bi bi-calendar-event me-2"></i>Total Agenda
                </h5>
                <p class="card-text display-6">{{ $agendaCount }}</p>
            </div>
        </div>
    </div>

    <div class="col-sm-12 col-md-4 mb-4">
        <div class="card shadow border-0 h-100">
            <div class="card-body">
                <h5 class="card-title text-primary">
                    <i class="bi bi-people-fill me-2"></i>Total User
                </h5>
                <p class="card-text display-6">{{ $userCount }}</p>
            </div>
        </div>
    </div>

    <div class="col-sm-12 col-md-4 mb-4">
        <div class="card shadow border-0 h-100">
            <div class="card-body">
                <h5 class="card-title text-primary">
                    <i class="bi bi-person-check me-2"></i>User Login
                </h5>
                <p class="card-text display-6">{{ auth()->user()->name }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Grafik --}}
<div class="card shadow border-0 mt-4">
    <div class="card-body">
        <h5 class="card-title text-primary">Statistik Agenda per Bulan</h5>
        <div class="table-responsive" style="height: 400px;">
            <canvas id="agendaChart"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const ctx = document.getElementById('agendaChart').getContext('2d');

        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(0, 123, 255, 0.9)');
        gradient.addColorStop(1, 'rgba(0, 123, 255, 0.3)');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Agenda per Bulan',
                    data: {!! json_encode($chartData) !!},
                    backgroundColor: gradient,
                    borderRadius: 8,
                    borderSkipped: false,
                    maxBarThickness: 50
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1200,
                    easing: 'easeOutBounce'
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#333',
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        callbacks: {
                            label: context => ` Total: ${context.parsed.y}`
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { size: 13 },
                            color: '#555'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#eaeaea',
                            borderDash: [3, 3]
                        },
                        ticks: {
                            font: { size: 13 },
                            color: '#555'
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
