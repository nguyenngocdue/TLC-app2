@props(['chartType'])

<div class="flex justify-center">
	<div class="block w-[{{$dimensions['width']}}px] h-[{{$dimensions['width']}}px]">
		<canvas id="{{$key}}"></canvas>
	</div>
</div>

@dump($meta)
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
  c[key]['element'] = document.getElementById('{{$key}}');
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
        position: 'top',
      },
      title: {
        display: true,
        text: 'Chart.js Bar Chart'
      }
    }
  },
  };
  


new Chart(c[key]['element'], c[key]['config'])
</script>
