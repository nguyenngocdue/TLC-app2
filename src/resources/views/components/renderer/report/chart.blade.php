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
  c[key]['element'] = document.getElementById('{{$key}}');
  c[key]['data'] = {
    labels: {!! $meta['labels'] !!},
    datasets: [
      {
        label:'',
        data: {!! $meta['numbers'] !!},
        backgroundColor: Object.values(COLORS),
        custom: {
          extraData: {!! $meta['href'] !!}
        }
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
      },
      // onHover: (event ,elements) =>{
      //   if(elements.length == 1){
      //     event.native.target.style.cursor = 'pointer';
      //   }
      //   if(elements.length == 0){
      //     event.native.target.style.cursor = 'default';
      //   }

      // },
      // onClick:  (event ,elements) => {
      //   if (elements.length) {
      //     var element = elements[0];
      //     var index = element.index;
      //     var datasetIndex = element.datasetIndex; 
      //     var hrefRedirect = c['{{$key}}']['config'].data.datasets[datasetIndex].custom.extraData[index];
      //     window.open(hrefRedirect);
      //   }
      // }
    },
  };
  


new Chart(c[key]['element'], c[key]['config'])
</script>
