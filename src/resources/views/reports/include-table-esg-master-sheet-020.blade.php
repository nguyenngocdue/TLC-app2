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
            background-color: #F3F4F6;
            border-color: #9ABAD9;
            border-style: solid;
            border-width: 1px;
            color: #4E5765;
            font-family: Arial, sans-serif;
            font-size: 16px;
            font-weight: normal;
            overflow: hidden;
            padding: 10px 10px;
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
            text-align: right;
            vertical-align: top
        }

        .tg .tg-0pky {
            border-color: inherit;
            text-align: right;
            vertical-align: middle padding: 8px 8px 8px 8px
        }

        .tg .tg-g7sd {
            border-color: inherit;
            font-weight: bold;
            text-align: left;
            vertical-align: middle
        }

    </style>
    @php
    $workplaces = App\Models\Workplace::all()->pluck('name', 'id')->toArray();
    $abbMonths = ["Jan","Feb","Mar","Apr","May","June","July","Aug","Sept","Oct","Nov","Dec"];
    @endphp

    {{-- @dd($tableDataSource); --}}

    <table class="tg">
        <thead>
            <tr>
                <th class="tg-7btt">Template</th>
                <th class="tg-7btt">Metric Type Name</th>
                <th class="tg-7btt">Unit</th>
                <th class="tg-7btt">State</th>
                <th class="tg-7btt">Workplace</th>
                <th class="tg-7btt">Total</th>
                @foreach($abbMonths as $abb)
                <th class="tg-7btt">{{$abb}}</th>

                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($tableDataSource as $k0 => $_dataSet)
            @php
            $dataSet = $_dataSet['array_metric_type'];
            @endphp
            @foreach($dataSet as $k1 => $items)
            <tr>
                @php
                $rowSpanMeType = $items["rowspan_metric_type"];
                $rowSpan = $k1 ? $items["rowspan_children"] : $_dataSet['rowspan'];
                @endphp

                @if(!$k1)
                <td class="tg-uzvj" rowspan="{{$rowSpan}}">{{$items['esg_tmpl_name']}}</td>
                @endif
                <td class="tg-uzvj" rowspan="{{$rowSpanMeType}}">{{$items['esg_metric_type_name']}}</td>
                <td class="tg-lboi" rowspan="{{$rowSpanMeType}}">{{$items['unit']}}</td>
                <td class="tg-lboi" rowspan="{{$rowSpanMeType}}">{{$items['state']}}</td>
                @php
                $fCalculatedNums = array_slice($items['calculated_numbers'], 0,1,true);
                $firstWorkplaceName = $workplaces[array_keys($fCalculatedNums)[0]];
                $calculatedNums = array_slice($items['calculated_numbers'],1,null,true);
                $totalPerMonths = $items['total_per_month'];
                @endphp
                {{-- Render first-line numberic for months --}}
                <td class="tg-fymr">{{$firstWorkplaceName}}</td>
                @foreach(reset($fCalculatedNums) as $k => $firstNum)
                @if($k)
                <td class="tg-0pky">{{$firstNum}}</td>
                @else <td class="tg-0pky bg-lime-500">{{$firstNum}}</td>
                @endif
                @endforeach
            </tr>

            {{-- Render numberic for months --}}
            @foreach($calculatedNums as $kwp => $numbers)
            <tr>
                <td class="tg-fymr">{{$workplaces[$kwp]}}</td>
                @foreach($numbers as $k => $secondNum)
                @if($k)
                <td class="tg-0pky">{{$secondNum}}</td>
                @else <td class="tg-0pky bg-lime-500">{{$secondNum}}</td>
                @endif
                @endforeach
            </tr>
            @endforeach
            {{-- Total per Months --}}
            <tr>
                <td class="tg-7btt" colspan="1">Total</td>
                @foreach($totalPerMonths as $totalNum)
                <td class="tg-fymr bg-lime-200">{{$totalNum}}</td>
                @endforeach
            </tr>

            @endforeach
            @endforeach

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
