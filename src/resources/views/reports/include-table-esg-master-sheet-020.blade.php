<div class="rounded-lg border-gray-950 border-2 overflow-hidden overflow-x-auto max-w-[1800px]  scrollbar-thumb-gray-100 ">

    <style type="text/css">
        .tg {
            border-collapse: collapse;
            border-color: #9ABAD9;
            border-spacing: 0;
        }

        .tg td {
            background-color: ;
            border-color: #9ABAD9;
            border-style: solid;
            border-width: 1px;
            color: #444;
            font-family: Arial, sans-serif;
            font-size: 14px;
            overflow: hidden;
            padding: 10px 5px;
            word-break: normal;
        }

        .tg th {
            background-color: #409cff;
            border-color: #9ABAD9;
            border-style: solid;
            border-width: 1px;
            color: #fff;
            font-family: Arial, sans-serif;
            font-size: 16px;
            font-weight: normal;
            overflow: hidden;
            padding: 10px 5px;
            word-break: normal;
        }

        .tg .tg-lboi {
            border-color: inherit;
            text-align: left;
            vertical-align: middle
        }

        .tg .tg-9wq8 {
            border-color: inherit;
            text-align: center;
            vertical-align: middle
        }

        .tg .tg-7btt {
            border-color: inherit;
            font-weight: bold;
            text-align: center;
            vertical-align: top
        }

        .tg .tg-uzvj {
            border-color: inherit;
            font-weight: bold;
            text-align: center;
            vertical-align: middle
        }

        .tg .tg-fymr {
            border-color: inherit;
            font-weight: bold;
            text-align: left;
            vertical-align: top
        }

        .tg .tg-0pky {
            border-color: inherit;
            text-align: left;
            vertical-align: top
        }

        .tg .tg-g7sd {
            border-color: inherit;
            font-weight: bold;
            text-align: left;
            vertical-align: middle
        }

    </style>
@php
	$workplaces = ["NZ-O","SG-O","VN-HO","VN-TF1","VN-TF2","VN-TF3"]
@endphp



    <table class="tg">
        <thead>
            <tr>
                <th class="tg-7btt">Template</th>
                <th class="tg-7btt">Metric Type Name</th>
                <th class="tg-7btt">Unit</th>
                <th class="tg-7btt">State</th>
                <th class="tg-7btt">Workplace</th>
                <th class="tg-7btt">Total 12 months</th>
                <th class="tg-7btt">Jan</th>
                <th class="tg-7btt">Feb</th>
                <th class="tg-7btt">Mar</th>
                <th class="tg-7btt">Apr</th>
                <th class="tg-7btt">May</th>
                <th class="tg-7btt">June</th>
                <th class="tg-7btt">July</th>
                <th class="tg-7btt">Aug</th>
                <th class="tg-7btt">Sept</th>
                <th class="tg-7btt">Oct</th>
                <th class="tg-7btt">Nov</th>
                <th class="tg-7btt">Dec</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="tg-uzvj" rowspan="7">1.3 Hazardous Waste</td>
                <td class="tg-uzvj" rowspan="6">01. Waste blasting material containing hazardous substances</td>
                <td class="tg-lboi" rowspan="6">kg</td>
                <td class="tg-lboi" rowspan="6">Solid</td>
				@foreach($workplaces as $fkwp => $firstWorkplace)
					<td class="tg-fymr">{{$firstWorkplace}}</td>
					<td class="tg-0pky">780</td>
					<td class="tg-0pky">10</td>
					<td class="tg-0pky">20</td>
					<td class="tg-0pky">30</td>
					<td class="tg-0pky">40</td>
					<td class="tg-0pky">50</td>
					<td class="tg-0pky">60</td>
					<td class="tg-0pky">70</td>
					<td class="tg-0pky">80</td>
					<td class="tg-0pky">90</td>
					<td class="tg-0pky">100</td>
					<td class="tg-0pky">110</td>
					<td class="tg-0pky">120</td>
					@break
				@endforeach
            </tr>
			@foreach($workplaces as $kwp => $workplace)
				@if(!$kwp) @continue  @endif
			<tr>
				<td class="tg-fymr">{{$workplace}}</td>
				<td class="tg-0pky">1280</td>
				<td class="tg-0pky">0</td>
				<td class="tg-0pky">10</td>
				<td class="tg-0pky">40</td>
				<td class="tg-0pky">56.66</td>
				<td class="tg-0pky">76.666</td>
				<td class="tg-0pky">96.66</td>
				<td class="tg-0pky">116.666</td>
				<td class="tg-0pky">136.66</td>
				<td class="tg-0pky">156.66</td>
				<td class="tg-0pky">176.66</td>
				<td class="tg-0pky">196.66</td>
				<td class="tg-0pky">216.66</td>
			</tr>
			@endforeach
            <tr>
                <td class="tg-fymr" colspan="3">colspan</td>
                <td class="tg-0pky">Total per Mon</td>
                <td class="tg-fymr">8209.5</td>
                <td class="tg-fymr">65.5</td>
                <td class="tg-fymr">59</td>
                <td class="tg-fymr">204</td>
                <td class="tg-fymr">335.67</td>
                <td class="tg-fymr">470.67</td>
                <td class="tg-fymr">605.67</td>
                <td class="tg-fymr">740.67</td>
                <td class="tg-fymr">875.67</td>
                <td class="tg-fymr">1010.7</td>
                <td class="tg-fymr">1145.7</td>
                <td class="tg-fymr">1280.7</td>
                <td class="tg-fymr">1415.7</td>
            </tr>


            <tr>
                <td class="tg-7btt" colspan="5">TOTAL</td>
                <td class="tg-fymr">32838</td>
                <td class="tg-fymr">262</td>
                <td class="tg-fymr">236</td>
                <td class="tg-fymr">816</td>
                <td class="tg-fymr">1342.7</td>
                <td class="tg-fymr">1882.7</td>
                <td class="tg-fymr">2422.7</td>
                <td class="tg-fymr">2962.7</td>
                <td class="tg-fymr">3502.7</td>
                <td class="tg-fymr">4042.7</td>
                <td class="tg-fymr">4582.7</td>
                <td class="tg-fymr">5122.7</td>
                <td class="tg-fymr">5662.7</td>
            </tr>

			
        </tbody>
    </table>

</div>
