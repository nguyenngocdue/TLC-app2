
<div class=" rounded-lg border-gray-950 border-2 overflow-hidden">
    <table class="tg whitespace-no-wrap w-full text-sm overflow-hidden">
        <thead class=''>
            <tr class="">
                <th class=" w-20{{$class2}} border-r" colspan=" 2">Category</th>
                <th class="w-[300px] p-2 {{$class2}}">Emission source category</th>
                <th class=" {{$class2}} border-l ">Source</th>
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
                <th class="{{$class2}} border-l">{{$value}} <br/>{{$params['year']}}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="w-20 text-center border-t border-r bg-white border-gray-500" rowspan="20">
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
                        {!! $firstItem['ghgtmpl_name'] ? "<a href='" . route('ghg_tmpls.edit', $ghgTmplId ?? 0) . "'>" . $firstItem['ghgtmpl_name'] . "</a>" : '' !!} 
                    </div>
                </td>
                {{-- Value --}}
                <td class="{{$class1}} text-right border-t">
                    <div class='p-2 font-bold'>
                        {!! is_null($firstItem['total_months']) ? "<i class='fa-light fa-minus'></i>": $firstItem['total_months'] !!}
                    </div>
                </td>

                {{-- Month --}}
                @foreach($firstItem['months'] as $key => $value)
                <td class='w-{{$widthCell}} border-l border-gray-500 text-right border-t text-blue-800'>
                    <div class='p-2'>
                        {!! (float)$value <= 0 ? "<i class='fa-light fa-minus'></i>" :"<a href='" . route('ghg_sheets.edit', $firstItem['month_ghg_sheet_id'][$key] ?? 0) . "'>" . $value . "</a>"  !!} 
                    </div>
                </td>
                @endforeach
                @foreach(array_values($remainingItem) as $values3)
            <tr>
                {{--Source--}}
                <td class="{{$class1}} text-left border-t text-blue-800">
                    <div class='p-2'>
                        {!! $values3['ghgtmpl_name'] ? "<a href='" . route('ghg_tmpls.edit', $values3['ghg_tmpl_id'] ?? 0) . "'>" . $values3['ghgtmpl_name'] . "</a>" : '' !!} </div>
                </td>
                {{-- Total Month (YTD) --}}
                <td class='w-{{$widthCell}} {{$class1}} text-right border-t'>
                    <div class='p-2 font-bold'>
                        {!! (float)$values3['total_months'] <= 0 ? "<i class='fa-light fa-minus'></i>": $values3['total_months'] !!}
                    </div>

                </td>
                {{-- Month --}}
                @foreach($values3['months'] as $k3 => $value)
                <td class='w-{{$widthCell}} border-l border-gray-500 text-right border-t text-blue-800'>
                    <div class='p-2'>
                        {!! (float)$value <= 0 ? "<i class='fa-light fa-minus'></i>" : "<a href='" . route('ghg_sheets.edit', $values3['month_ghg_sheet_id'][$k3] ?? 0) . "'>$value</a>" !!} </div>
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
                <td class="bg-white border-t  border-gray-500" colspan="2"></td>
                <td class="{{$class1}} text-left border-t font-bold">Total Emissions</td>
                @foreach($totalEmissions as $value)
                <td class=" text-right border-t border-gray-500">
                    <div class='p-2 font-bold text-red-600'>
                        {!! (float)$value <= 0 ? "<i class='fa-light fa-minus'></i>": $value !!}
                    </div>
                </td>
                @endforeach
            </tr>
        </tbody>
    </table>
</div>
