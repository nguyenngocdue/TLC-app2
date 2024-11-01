@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

@php 
$currentDate = str_replace('Nguyen', 'Mr.', 'Nguyen Ngoc Due');
$fromDate = new DateTime('2024-09-18 08:58:59');
$fromDate = $fromDate->format('Y-m-d');

@endphp
@php
$entityType = '1a';
@endphp
{{-- <html :class="{ 'dark': isDark }" x-data="alpineData()" x-ref="alpineRef"  lang="en"> --}}

@once
   <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/hammerjs@2.0.8"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-zoom/2.0.1/chartjs-plugin-zoom.min.js"></script>
@endonce
<div class="w-1/2">
     <canvas id="reworkChart" width="800" height="400"></canvas>
</div>

    <script>
        const ctx = document.getElementById('reworkChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [
                    "001. PPR", "002. Structural", "003.1 Fit Out", "003.2 Finishing", 
                    "003.3 FFE", "003.4 Tiling", "003.5 Subcon", "004.1 MEP 1st", 
                    "004.2 MEP 2nd", "003.6 PAW", "Total"
                ],
                datasets: [
                    {
                        label: 'Design',
                        data: [82, 0, 846, 0, 0, 566, 34, 236, 17, 0, 1781],
                        backgroundColor: function(context) {return createColorCols(context, "#7e22e0")},
                    },
                    {
                        label: 'Workmanship',
                        data: [10, 30, 0, 0, 0, 0, 0, 0, 0, 0, 40], // Placeholder data
                        backgroundColor: function(context) {return createColorCols(context, "#E615A3", "#38e022")},
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    title: {
                        display: true,
                        text: 'Total Rework Hours (Design/Workmanship) by Discipline'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Hours'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Disciplines'
                        }
                    }
                }
            }
        });
    </script>




                
@endsection
