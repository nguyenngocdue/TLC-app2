@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $titleReport)
@section('tooltip', Str::ucfirst($typeReport)." ".$currentMode)
@section('content')

{{-- <div class="px-4">
    <div class="justify-end pb-5"></div>
    <div class="w-full no-print rounded-lg border bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 mb-5 p-3">
        <label for="" class="flex flex-1 text-gray-700 text-lg font-bold dark:text-white">Advanced Filter</label>
        <x-reports.parameter3-report :itemsSelected="$params" modeOption="{{$currentMode}}" :columns="$paramColumns" routeName="{{$routeName}}" typeReport="{{$typeReport}}" entity="{{$entity}}" />
    </div>
</div> --}}
<br/>
<div class="flex justify-center bg-only-print">
    <div>
        <style type="text/css">
            .tg  {border-collapse:collapse;border-spacing:0;}
            .tg td{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
              overflow:hidden;padding:10px 5px;word-break:normal;}
            .tg th{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
              font-weight:normal;overflow:hidden;padding:10px 5px;word-break:normal;}
            .tg .{text-align:left;vertical-align:top}
            </style>
            <table class="tg">
            <thead>
              <tr class="">
                <th class="w-12" colspan="2">Category</th>
                <th class="w-[450px]">Emission source category</th>
                <th class="">Source</th>
                <th class="">t CO2e</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="w-10" rowspan="10">
                    <div class="">GHG Protocol Standards: Corporate Scope - 1 and 2, Value Chain - Scope 3</div>
                </td>
                <td class="" rowspan="2">Scope 1</td>
                <td class="">1.1 Direct emissions arising from owned or controlled stationary sources that use fossil fuels and/or emit fugitive emissions</td>
                <td class=""></td>
                <td class=""></td>
              </tr>
              <tr>
                <td class="">1.2 Direct emissions from owned or controlled mobile sources</td>
                <td class=""></td>
                <td class=""></td>
              </tr>
              <tr>
                <td class="">Scope 2</td>
                <td class="">2.1 Location-based emissions from the generation of purchased electricity and transmission &amp; distrubution loss</td>
                <td class=""></td>
                <td class=""></td>
              </tr>
              <tr>
                <td class="" rowspan="7">Scope 3</td>
                <td class="">3.1 Purchased goods</td>
                <td class=""></td>
                <td class=""></td>
              </tr>
              <tr>
                <td class="">3.2 Waste generated in operations</td>
                <td class=""></td>
                <td class=""></td>
              </tr>
              <tr>
                <td class="">3.3 Business travel</td>
                <td class=""></td>
                <td class=""></td>
              </tr>
              <tr>
                <td class="">3.4 Upstream transportation and distribution</td>
                <td class=""></td>
                <td class=""></td>
              </tr>
              <tr>
                <td class="">3.5 Commuting</td>
                <td class=""></td>
                <td class=""></td>
              </tr>
              <tr>
                <td class="">3.6 Manage Assets</td>
                <td class=""></td>
                <td class=""></td>
              </tr>
              <tr>
                <td class="">3.7 Home office</td>
                <td class=""></td>
                <td class=""></td>
              </tr>
              <tr>
                <td class="" colspan="3"></td>
                {{-- <td class=""></td> --}}
                {{-- <td class=""></td> --}}
                <td class="">Total Emissions</td>
                <td class=""></td>
              </tr>
            </tbody>
            </table>

           

    </div>

</div>






@endsection
