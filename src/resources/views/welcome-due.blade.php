@extends('layouts.app')

@section('content')

@once
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.min.js"></script>
@endonce

<div class="flex justify-center">
	<div class="block">
		<canvas id="myChart" width=400 height=400></canvas>
	</div>
	<button class="bg-slate-400" type="button" id="resetZoom">reset zoom</button>
</div>

@once
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/hammerjs@2.0.8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-zoom/2.0.1/chartjs-plugin-zoom.min.js"></script>

@endonce

<script>
    const ctx = document.getElementById('myChart');

    var chart = new Chart(ctx, {
        type: 'bar'
        , data: {
            labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange', 'Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange',
			'Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange', 'Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange']
            , datasets: [{
                label: '# of Votes'
                , data: [12, 19, 3, 5, 2, 3, 12, 19, 3, 5, 2, 3, 12, 19, 3, 5, 2, 3, 12, 19, 3, 5, 2, 3]
                , borderWidth: 1
            }]
        }
        , options: {
			plugins:{
				zoom: {
					zoom: {
					wheel: {
						enabled: true,
					},
					pinch: {
						enabled: true
					},
					mode: 'xy',
					}
				},
			},
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
	 document.getElementById('resetZoom').addEventListener('click', function () {
        chart.resetZoom(); // Gửi lệnh phục hồi zoom
    });

</script>


@endsection

