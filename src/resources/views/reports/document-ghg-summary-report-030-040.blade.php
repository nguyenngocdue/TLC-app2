@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $titleReport)
@section('tooltip', Str::ucfirst($typeReport)." ".$currentMode)
@section('content')


@php
$widthCell = 50;
$class1 = "bg-white dark:border-gray-600 border-r";
$class2 =" bg-gray-100 px-4 py-3 border-gray-300 ";
$typeTimes = isset($params['only_month']) ? $params['only_month']: $params['quarter_time'];
$topNameCol = isset($params['only_month']) ? '' : 'QTR';
$columnName = isset($params['only_month']) ? 'months' : 'quarters';
$years = $params['year'];
@endphp

<div class="px-4">
    @include('components.reports.shared-parameter')
</div>

<div class="flex justify-center bg-only-print">
    <div class="border rounded-lg border-gray-300 dark:border-gray-600 overflow-hidden">
        <table class="tg whitespace-no-wrap w-full text-sm">
            <thead class='border-b'>
                <tr>
                    <th class=" w-20{{$class2}}" colspan=" 2">Category</th>
                    <th class="w-[300px] p-2 {{$class2}} border-l ">Emission source category</th>
                    <th class=" {{$class2}} border-l  ">Source</th>

                    @foreach($typeTimes as $value)
                    <th id="" colspan="{{count($years)}}" class="border">
                        <div class="text-gray-700 dark:text-gray-300">
                            <span>
                                {{$topNameCol}}
                                @php
                                    $value = $topNameCol ? $value:  App\Utils\Support\DateReport::getMonthAbbreviation($value);
                                @endphp
                                {{$value}}
                            </span></div>
                    </th>
                    @endforeach
                </tr>
            </thead>

            <thead class="sticky z-10 top-10">
                <tr class="bg-gray-100 text-center text-xs font-semibold tracking-wide text-gray-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300">
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    @for ($i = 0; $i < count($typeTimes); $i++) 
                        @foreach ($years as $value) 
                            <th class="bg-gray-100 px-4 py-3 border-gray-300 border-l border-r border-t">{{ $value }}</th>
                        @endforeach
                    @endfor
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="w-20 {{$class1}} text-center border-t" rowspan="20">
                        <div class="p-2 font-bold">GHG Protocol Standards: Corporate Scope - 1 and 2, Value Chain - Scope 3</div>
                    </td>
                </tr>
                {{-- Begin Row --}}
                @foreach($tableDataSource['scopes'] as $keyScope => $values)
                @php
                if(!is_numeric($keyScope)) continue;
                $rowSpanOutSide = $settingComplexTable[$keyScope]['scope_rowspan'];
                $remainingItem = [];
                $scopeName = \App\Models\Term::find($keyScope)->toArray()['name'];
                @endphp
                <tr>
                    <td class=" w-20 {{$class1}} text-center border-t" rowspan="{{$rowSpanOutSide}}">{{$scopeName}}</td>
                    @foreach($values as $ghgcateId => $values2)
                    @php
                    $rowSpanInside = count($values2);
                    $firstItem = $values2[0];
                    $ghgTmplId = $firstItem['ghg_tmpl_id'];
                    $remainingItem = array_splice($values2, 1);
                    @endphp
                    {{--Emission source category --}}
                    <td class="{{$class1}} text-left border-t" rowspan="{{$rowSpanInside}}">
                        <div class='p-2'>
                            {{$firstItem['ghgcate_name']}}
                        </div>
                    </td>
                    {{-- Source Column --}}
                    <td class="{{$class1}} text-left border-t text-blue-800">
                         <div class='p-2'>
                            {!! $firstItem['ghgtmpl_name'] ? "<a href='" . route('ghg_tmpls.edit', $ghgTmplId ?? 0) . "'>" . $firstItem['ghgtmpl_name'] . "</a>" : '' !!} </div>
                    </td>
                    {{-- Quarter Number --}}
                    @if(isset($firstItem[$columnName]))
                        @for ($i = 0; $i < count($typeTimes); $i++) 
                            @foreach(array_values($firstItem[$columnName])[$i] as $value)
                                <td class='w-{{$widthCell}} {{$class1}} text-right border-t'>
                                    <div class='p-2'>{{$value}}</div>
                                </td>
                            @endforeach
                        @endfor
                    @else
                     <td class='w-{{$widthCell}} {{$class1}} text-right border-t'>
                         <div class='p-2'></div>
                     </td>
                    @endif
                    
                    @foreach(array_values($remainingItem) as $values3)
                </tr>
                <tr>
                    {{--Source--}}
                    <td class="{{$class1}} text-left border-t text-blue-800">
                        <div class='p-2'>
                            {!! $values3['ghgtmpl_name'] ? "<a href='" . route('ghg_tmpls.edit', $values3['ghg_tmpl_id'] ?? 0) . "'>" . $values3['ghgtmpl_name'] . "</a>" : '' !!} </div>
                    </td>
                    {{-- Quarter Number --}}
                   @for ($i = 0; $i < count($typeTimes); $i++) 
                            @foreach(array_values($values3[$columnName])[$i] as $value)
                                <td class='w-{{$widthCell}} {{$class1}} text-right border-t'>
                                    <div class='p-2'>{{$value}}</div>
                                </td>
                            @endforeach
                    @endfor
                </tr>
                @endforeach
                </tr>
                @endforeach
                @endforeach
                 <tr>
                    {{-- End Row --}}
                    @php
                    $totalEmissions = array_values($tableDataSource['total_emission']);
                    @endphp
                    <td class="bg-white border-t" colspan="2"></td>
                    <td class="{{$class1}} text-left border-t font-bold">Total Emissions</td>
                            @foreach(array_values($totalEmissions) as $value)
                                @for ($i = 0; $i < count($typeTimes); $i++)
                                    <td class='w-{{$widthCell}} {{$class1}} text-right border-t'>
                                        <div class='p-2'>{{array_values($value)[$i]}}</div>
                                    </td>
                                @endfor
                            @endforeach
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection
