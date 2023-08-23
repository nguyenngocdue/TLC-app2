@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $titleReport)
@section('tooltip', Str::ucfirst($typeReport)." ".$currentMode)
@section('content')
{{-- @dd($tableDataSource ,$settingComplexTable) --}}
<div class="px-4">
    <div class="justify-end pb-5"></div>
    <div class="w-full no-print rounded-lg border bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 mb-5 p-3">
        <label for="" class="flex flex-1 text-gray-700 text-lg font-bold dark:text-white">Advanced Filter</label>
        <x-reports.parameter3-report :itemsSelected="$params" modeOption="{{$currentMode}}" :columns="$paramColumns" routeName="{{$routeName}}" typeReport="{{$typeReport}}" entity="{{$entity}}" />
    </div>
</div>
<br />
<div class="flex justify-center bg-only-print">
    <div>
        <style type="text/css">
            .tg {
                border-collapse: collapse;
                border-spacing: 0;
            }

            .tg td {
                border-color: black;
                border-style: solid;
                border-width: 1px;
                font-family: Arial, sans-serif;
                font-size: 14px;
                overflow: hidden;
                padding: 10px 5px;
                word-break: normal;
            }

            .tg th {
                border-color: black;
                border-style: solid;
                border-width: 1px;
                font-family: Arial, sans-serif;
                font-size: 14px;
                font-weight: normal;
                overflow: hidden;
                padding: 10px 5px;
                word-break: normal;
            }

            .tg . {
                text-align: left;
                vertical-align: top
            }

        </style>
        <table class="tg">
            <thead>
                <tr class="">
                    <th class="w-12" colspan="2">Category</th>
                    <th class="w-[450px]">Emission source category</th>
                    <th class="">Source</th>
                    <th class="">Total</th>
                    @php
                        $month = array_slice($tableDataSource['total_emission'],1, null, true);
                    @endphp
                    @foreach(array_keys($month) as $value)
                    <td>{{$value}}</td>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="w-10" rowspan="20">
                        <div class="">GHG Protocol Standards: Corporate Scope - 1 and 2, Value Chain - Scope 3</div>
                    </td>
                </tr>
                {{-- Point --}}

                @foreach($tableDataSource as $keyScope => $values)
                @php
                if(!is_numeric($keyScope)) continue;
                $rowSpanOutSide = $settingComplexTable[$keyScope]['scope_rowspan'];
                $remainingItem = [];
                $scopeName = \App\Models\Term::find($keyScope)->toArray()['name'];
                @endphp
                <tr>
                    <td class="" rowspan="{{$rowSpanOutSide}}">{{$scopeName}}</td>
                    @foreach($values as $ghgcateId => $values2)
                    @php
                    $rowSpanInside = count($values2);
                    $firstItem = $values2[0];
                    $remainingItem = array_splice($values2, 1);
                    @endphp
                    <td class="" rowspan="{{$rowSpanInside}}">{{$firstItem['ghgcate_name']}}</td>
                    <td class="">{{$firstItem['ghgtmpl_name']}}</td>
                    <td class="">{{(string)$firstItem['total_months'] === '0' ? '': $firstItem['total_months']}}</td>
                    @foreach(array_values($firstItem['months']) as $value)
                     <td>{{$value === '0'? '': $value}}</td>
                    @endforeach
                    @foreach($remainingItem as $k3 => $values3)
                <tr>
                    <td class="">{{$values3['ghgtmpl_name']}}</td>
                    <td class="">{{(string)$firstItem['total_months'] === '0' ? '': $firstItem['total_months']}}</td>
                    @foreach(array_values($values3['months']) as $value)
                    <td>{{$value === '0'? '': $value}}</td>
                    @endforeach
                </tr>
                @endforeach
                </tr>
                @endforeach
                @endforeach
                <tr>
                @php
                    $totalEmissions = $tableDataSource['total_emission'];
                @endphp
                    <td class="" colspan="2"></td>
                    <td class="">Total Emissions</td>
                     @foreach($totalEmissions as $value)
                     <td>{{(string)$value === '0'? '': $value}}</td>
                    @endforeach
                </tr>
            </tbody>
        </table>




    </div>

</div>






@endsection
