@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $titleReport)
@section('tooltip', Str::ucfirst($typeReport)." ".$currentMode)
@section('content')
{{-- @dd($tableDataSource ,$settingComplexTable) --}}
{{-- CLASS --}}
@php
$class1 = "bg-white dark:border-gray-600 border-r";
$class2 =" bg-gray-100 px-4 py-3 border-gray-300";
$widthCell = 50
@endphp


<div class="px-4">
    <div class="justify-end pb-5"></div>
    <div class="w-full no-print rounded-lg border bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 mb-5 p-3">
        <label for="" class="flex flex-1 text-gray-700 text-lg font-bold dark:text-white">Advanced Filter</label>
        <x-reports.parameter3-report :itemsSelected="$params" modeOption="{{$currentMode}}" :columns="$paramColumns" routeName="{{$routeName}}" typeReport="{{$typeReport}}" entity="{{$entity}}" />
    </div>
</div>
<br />
<div class="flex justify-center bg-only-print">
    <div class="border rounded-lg border-gray-300 dark:border-gray-600 overflow-hidden">
        <table class="tg whitespace-no-wrap w-full text-sm">
            <thead class=''>
                <tr class="">
                    <th class=" w-20 bg-gray-100 {{$class2}} " colspan=" 2">Category</th>
                    <th class="w-[300px] p-2   bg-gray-100 {{$class2}}  ">Emission source category</th>
                    <th class="  bu-gray-100 {{$class2}}  ">Source</th>
                    <th class="  bg-gray-100 {{$class2}}  ">{{isset($params['quarter_time']) ? 'QTR' : 'YTD'}}</th>
                    @php
                    $month = array_slice($tableDataSource['total_emission'],1, null, true);
                    @endphp
                    @foreach(array_keys($month) as $value)
                    <th class="bg-gray-100 {{$class2}} border-l">{{$value}}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="w-20 {{$class1}} text-center border-t" rowspan="20">
                        <div class="p-2 font-bold">GHG Protocol Standards: Corporate Scope - 1 and 2, Value Chain - Scope 3</div>
                    </td>
                </tr>
                {{-- Begin Row --}}
                @foreach($tableDataSource as $keyScope => $values)
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
                        <a href='{{ route("ghg_tmpls.edit", $ghgTmplId ?? 0) }}'>
                            <div class='p-2'>
                                {{$firstItem['ghgtmpl_name']}}
                            </div>
                        </a>
                    </td>
                    <td class="{{$class1}} text-right border-t">
                        <div class='p-2'>
                            {{(string)$firstItem['total_months'] === '0' ? '': $firstItem['total_months']}}
                        </div>
                    </td>
                    {{-- End Source Column --}}

                    {{-- Month --}}
                    @foreach($firstItem['months'] as $key => $value)
                    <td class='w-{{$widthCell}} {{$class1}} text-right border-t text-blue-800'>
                        <a href='{{ route("ghg_sheets.edit", $firstItem['month_ghg_sheet_id'][$key] ?? 0)}}'>
                            <div class='p-2'>
                                {{$value === '0'? '': $value}}
                            </div>
                        </a>
                    </td>
                    @endforeach
                    @foreach(array_values($remainingItem) as $values3)
                <tr>
                    {{--Source--}}
                    <td class="{{$class1}} text-left border-t text-blue-800">
                        <a href='{{ route("ghg_tmpls.edit", $values3['ghg_tmpl_id']) }}'>
                            <div class='p-2'>
                                {{$values3['ghgtmpl_name']}}
                            </div>
                        </a>
                    </td>
                    {{-- Month --}}
                    <td class='w-{{$widthCell}} {{$class1}} text-right border-t'>
                        <div class='p-2'>
                            {{(string)$values3['total_months'] === '0' ? '': $values3['total_months']}}
                        </div>

                    </td>
                    @foreach($values3['months'] as $k3 => $value)
                    {{-- @dd($values3); --}}
                    <td class='w-{{$widthCell}} {{$class1}} text-right border-t text-blue-800'>
                        <a href='{{ route("ghg_sheets.edit", $values3['month_ghg_sheet_id'][$k3] ?? 0) }}'>
                            <div class='p-2'>
                                {{$value === '0'? '': $value}}
                            </div>
                        </a>
                    </td>
                    @endforeach
                </tr>
                @endforeach
                </tr>
                @endforeach
                @endforeach
                <tr>
                    {{-- End Row --}}
                    @php
                    $totalEmissions = $tableDataSource['total_emission'];
                    @endphp
                    <td class="bg-white border-t" colspan="2"></td>
                    <td class="{{$class1}} text-left border-t">Total Emissions</td>
                    @foreach($totalEmissions as $value)
                    <td class="{{$class1}} text-right border-t">
                        <div class='p-2'>
                            {{(string)$value === '0'? '': $value}}
                        </div>
                    </td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
