@props(['chartType'])

<div class="block"><canvas id="{{$key}}"></canvas></div>

{{-- @dump($meta) --}}
{{-- @dump($metric) --}}
{{-- @dump($chartType) --}}

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
  type: '{{$chartType}}',
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
c[key]['element'] = document.getElementById('{{$key}}');
new Chart(c[key]['element'], c[key]['config'])
</script>