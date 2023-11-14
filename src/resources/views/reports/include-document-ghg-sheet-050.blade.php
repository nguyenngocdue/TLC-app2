<div class="flex justify-center bg-only-print">
    <div class="border rounded-lg border-gray-300 dark:border-gray-600 overflow-hidden">
        <table class="tg whitespace-no-wrap w-full text-sm">
            <thead class=''>
                <tr class="">
                    <th class=" w-20{{$class2}}" colspan=" 2">Category</th>
                    <th class="w-[300px] p-2 {{$class2}} border-l ">Emission source category</th>
                    <th class=" {{$class2}} border-l  ">Source</th>
                    <th class=" {{$class2}} border-l  ">Metric 0</th>
                    <th class=" {{$class2}} border-l  ">Metric Type 1</th>
                    <th class=" {{$class2}} border-l  ">Metric Type 2</th>
                    <th class=" {{$class2}} border-l">
                        {{$titleColName}}<br>(tCO2e)</br>
                    </th>
                    @php
                    $month = array_slice($tableDataSource['total_emission'],1, null, true);
                    @endphp
                    @foreach(array_keys($month) as $value)
                    @php
                        $value = App\Utils\Support\DateReport::getMonthAbbreviation2((int)$value);
                    @endphp
                    <th class="{{$class2}} border-l">{{$value}}</th>
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

                    {{-- Render Detail for Metric --}}
                    @php
                        $childrenMetrics = $firstItem['children_metrics'] ?? [];
                    @endphp
                        {{-- Metric Type --}}
                        <td class="{{$class1}} text-center bg-white">
                            @foreach($childrenMetrics as $child => $metric)
                                <div class='p-2 dark:border-gray-600 border-b h-full'>
                                            {!! $metric['ghg_metric_type_name'] ?? '<i class="fa-solid fa-minus"></i>' !!}
                                </div>
                            @endforeach 
                        </td>
                        {{-- Metric Type 1 --}}
                        <td class="{{$class1}} text-center bg-white">
                            @foreach($childrenMetrics as $child => $metric)
                                        <div class='p-2 dark:border-gray-600 border-b h-full'>
                                            {!! $metric['ghg_metric_type_1_name'] ?? '<i class="fa-solid fa-minus"></i>' !!}
                                        </div>
                            @endforeach 
                        </td>
                        {{-- Metric Type 2 --}}
                        <td class="{{$class1}} text-center bg-white">
                                @foreach($childrenMetrics as $child => $metric)
                                    <div class='p-2 dark:border-gray-600 border-b h-full'>
                                        {!! $metric['ghg_metric_type_2_name'] ?? '<i class="fa-solid fa-minus"></i>' !!}
                                    </div>
                                @endforeach 
                        </td>

                    {{-- Total Month (YTD) --}}
                        <td class='w-{{$widthCell}} {{$class1}} text-right border-t'>
                            @foreach($childrenMetrics as $key => $value)
                                <div class='p-2 font-bold dark:border-gray-600 border-t'>
                                    @if((string)$value['total_months'] === '0')
                                        <i class="fa-solid fa-minus"></i>
                                    @else
                                        {{ $value['total_months'] }}
                                    @endif
                                </div>
                            @endforeach
                        </td>

                    {{-- Month --}}
                    @if(isset($firstItem['children_metrics']))
                        @php
                            $d = $firstItem['children_metrics'];
                            $monthData = last($d)['months'];
                        @endphp
                            @foreach($monthData as $m => $val)
                            <td class='w-{{$widthCell}} {{$class1}} text-right border-t text-blue-800'>
                                @foreach($d as $k => $item)
                                    <div class='p-2 font-bold dark:border-gray-600 border-t'>
                                        @if((string)$item['months'][$m] === '0')
                                            <i class="fa-solid fa-minus"></i>
                                        @else
                                           {{$item['months'][$m]}}
                                        @endif
                                    </div>
                                @endforeach
                            </td>
                            @endforeach
                    @endif
                    {{--END--}}

                    @foreach(array_values($remainingItem) as $values3)
                <tr>
                    {{--Source--}}
                    <td class="{{$class1}} text-left border-t text-blue-800">
                        <div class='p-2'>
                            {!! $values3['ghgtmpl_name'] ? "<a href='" . route('ghg_tmpls.edit', $values3['ghg_tmpl_id'] ?? 0) . "'>" . $values3['ghgtmpl_name'] . "</a>" : '' !!} </div>
                    </td>
                
                    {{-- Render Detail for Metric --}}
                    @php
                        $childrenMetrics = $values3['children_metrics'] ?? [];
                    @endphp
                        {{-- Metric Type --}}
                        <td class="{{$class1}} text-center bg-white">
                            @foreach($childrenMetrics as $child => $metric)
                                    <div class='p-2 dark:border-gray-600 border-b h-full'>
                                        {{$metric['ghg_metric_type_name'] ?? 'NUll'}}
                                    </div>
                            @endforeach 
                        </td>
                        {{-- Metric Type 1 --}}
                        <td class="{{$class1}} text-center bg-white">
                                @foreach($childrenMetrics as $child => $metric)
                                        <div class='p-2 dark:border-gray-600 border-b h-full'>
                                            {{$metric['ghg_metric_type_1_name'] ?? 'Null'}}
                                        </div>
                                @endforeach 
                        </td>
                        {{-- Metric Type 2 --}}
                        <td class="{{$class1}} text-center bg-white">
                                @foreach($childrenMetrics as $child => $metric)
                                        <div class='p-2 dark:border-gray-600 border-b h-full'>
                                             {{$metric['ghg_metric_type_2_name'] ?? 'Null'}}
                                        </div>
                                @endforeach 
                        </td>
                    {{--  --}}

                    @if(isset($values3['children_metrics']))
                        {{-- Total Month (YTD) --}}
                        <td class='w-{{$widthCell}} {{$class1}} text-right border-t'>
                            @foreach($values3['children_metrics'] as $key => $value)
                                <div class='p-2 font-bold dark:border-gray-600 border-t'>
                                        @if((string)$value['total_months'] === '0')
                                            <i class="fa-solid fa-minus"></i>
                                        @else
                                            {{ $value['total_months']}}
                                        @endif
                                </div>
                            @endforeach
                        </td>
                    
                        {{-- Month --}}
                        @php
                            $childrenMetricsVal3 = $values3['children_metrics'];
                            $monthData = last($childrenMetricsVal3)['months'];
                        @endphp
                            @foreach($monthData as $m => $val)
                            <td class='w-{{$widthCell}} {{$class1}} text-right border-t text-blue-800'>
                                @foreach($childrenMetricsVal3 as $k => $item)
                                         <div class='p-2 font-bold dark:border-gray-600 border-t'>
                                            @if((string)$item['months'][$m] === '0')
                                                <i class="fa-solid fa-minus"></i>
                                            @else
                                                {{$item['months'][$m]}}
                                            @endif
                                        </div>
                                @endforeach
                            </td>
                            @endforeach
                        
                    @endif
                    {{--END--}}


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
                    <td class="bg-white border-t" colspan="5"></td>
                    <td class="{{$class1}} text-left border-t font-bold">Total Emissions</td>
                    @foreach($totalEmissions as $value)
                    <td class="{{$class1}} text-right border-t">
                        <div class='p-2 font-bold text-red-600'>
                            {{(string)$value === '0'? '': $value}}
                        </div>
                    </td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
</div>
