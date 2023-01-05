@props(['title', 'figure'])

@php
    $title = $title ?? "Untitled";
    $figure = $figure ?? "???";
@endphp

<div class="shadow-xs flex items-center  bg-white p-4 dark:bg-gray-800 ">
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
</div>
<div class="block"><canvas id="{{$key}}"></canvas></div>

{{-- @dump($meta) --}}
{{-- @dump($metric) --}}

@once
<script>
const COLORS = ['#4dc9f6','#f67019','#f53794','#537bc4','#acc236','#166a8f','#00a950','#58595b','#8549ba'];
var c = {}
</script>
@endonce

<script>
var key = '{{$key}}'
c[key]={}
c[key]['data'] = {
  labels: {!! $meta['labels'] !!},
  datasets: [
    {
      label:'',
      data: {!! $meta['numbers'] !!},
      backgroundColor: Object.values(COLORS),
    }
  ]
};
c[key]['config'] = {
  type: 'doughnut',
  data: c[key]['data'],
  options: {
    responsive: true,
    plugins: {
      legend: {
        position: 'bottom',
      },
      title: {
        display: false,
        text: 'Chart.js Doughnut Chart'
      }
    }
  },
};
c[key]['ctx'] = document.getElementById('{{$key}}');
new Chart(c[key]['ctx'], c[key]['config'])
</script>