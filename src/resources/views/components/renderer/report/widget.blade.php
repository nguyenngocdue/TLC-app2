@props(['title', 'figure'])

@php
    $title = $title ?? "Untitled";
    $figure = $figure ?? "???";
@endphp

<div class="shadow-xs flex items-center rounded-lg bg-white p-4 dark:bg-gray-800 border">
    <div class="flex">
        <div class="mr-4 rounded-full bg-orange-100 p-3 text-orange-500 dark:bg-orange-500 dark:text-orange-100">
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
        </div>
        <div>
            <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                {{$title}}
            </p>
            <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                {{$figure}}
            </p>
        </div>
    </div>
    <div class="block">
        <canvas id="myChart"></canvas>
        <canvas id="myChart1"></canvas>
    </div>
</div>
<div>
    
    <script>
    const ctx = document.getElementById('myChart');
    const ctx1 = document.getElementById('myChart1');
    
    new Chart(ctx, {
        type: 'bar',
        data: {
        labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
        datasets: [{
            label: '# of Votes',
            data: [12, 19, 3, 5, 2, 3],
            borderWidth: 1
        }]
        },
        options: {
        scales: {
            y: {
            beginAtZero: true
            }
        }
        }
    });

    const data1 = {
  labels: [
    'Red',
    'Blue',
    'Yellow'
  ],
  datasets: [{
    label: 'My First Dataset',
    data: [300, 50, 100],
    backgroundColor: [
      'rgb(255, 99, 132)',
      'rgb(54, 162, 235)',
      'rgb(255, 205, 86)'
    ],
    hoverOffset: 4
  }]
};

const config = {
  type: 'doughnut',
  data: data1,
};

new Chart(ctx1, config)
    </script>
</div>
