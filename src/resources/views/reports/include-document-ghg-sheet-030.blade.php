<x-renderer.heading level=2 class='text-center'>Reports for Each Sheet</x-renderer.heading>
<div class="rounded-lg border-gray-950 border-2 overflow-hidden overflow-x-auto max-w-[1800px]  scrollbar-thumb-gray-100">
	<table class="tg whitespace-no-wrap w-full text-sm overflow-hidden border-gray-900">
		<thead class='border-b'>
			<tr>
				<th class="tracking-wide  w-20 {{$class2}} border-b" colspan=" 2">Category</th>
				<th class="tracking-wide w-[300px] p-2 border-l {{$class2}} border-b">Emission source category</th>
				<th class="tracking-wide border-l {{$class2}} border-b">Source</th>
				@switch($columnName)
					@case("years")
						<th id="" colspan="{{count($years)}}" class=" border-l bg-gray-100 py-2 border-gray-600 border-b">
								<div class=" dark:border-gray-600 ">
									<span>Year </br><p class="font-normal">(tCO2e)</p></span>
								</div>
							</th>
						@break
					@case("months" || "quarter")
							@foreach($timeValues as $value)
							<th id="" colspan="{{count($years)}}" class=" border-l bg-gray-100 py-2 border-gray-600 border-b">
								<div class=" dark:border-gray-600 ">
										@php
											$value = $topNameCol ? $value:  App\Utils\Support\DateReport::getMonthAbbreviation($value);
										@endphp
									<span>
										{{$topNameCol}}{{$value}}</br><p class='font-normal'>(tCO2e)</p>
									</span></div>
							</th>
							@endforeach
						@break
					@default
					@break    
				@endswitch

			</tr>
		</thead>

		<thead class="sticky z-10 top-10">
			<tr class="bg-gray-100 text-center text-xs font-semibold tracking-wide text-gray-500 border">
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				@switch($columnName)
					@case("years")
							@for ($i = 0; $i < count($timeValues); $i++) 
								<th class=" bg-gray-100 px-4 py-3 border-gray-600 border-l border-t text-base tracking-wide">{{ $years[$i] }}</th>
							@endfor
						@break
					@case("months" || "quarters")
							@for ($i = 0; $i < count($timeValues); $i++) 
								@foreach ($years as $value) 
									<th class=" bg-gray-100 px-4 py-3 border-gray-600 border-l border-t text-base tracking-wide">{{ $value }}</th>
								@endforeach
							@endfor
						@break
					@default
					@break    
				@endswitch
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="w-20 text-center border-t border-gray-600" rowspan="20">
					<div class=" font-bold">GHG Protocol Standards: Corporate Scope - 1 and 2, Value Chain - Scope 3</div>
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
				<td class=" w-26 {{$class1}} text-center border-t  text-base tracking-wide" rowspan="{{$rowSpanOutSide}}">{{$scopeName}}</td>
				@foreach($values as $ghgcateId => $values2)
				@php
				$rowSpanInside = count($values2);
				$firstItem = $values2[0];
				$ghgTmplId = $firstItem['ghg_tmpl_id'];
				$remainingItem = array_splice($values2, 1);
				@endphp
				{{--Emission source category --}}
				<td class="{{$class1}} text-left border-t text-base tracking-wide" rowspan="{{$rowSpanInside}}">
					<div class='w-96'>
						{{$firstItem['ghgcate_name']}}
					</div>
				</td>
				{{-- Source Column --}}
				<td class="{{$class1}}  text-left border-t text-base tracking-wide text-blue-800 w-96">
					<div class='w-96'>
						{!! $firstItem['ghgtmpl_name'] ? "<a href='" . route('ghg_tmpls.edit', $ghgTmplId ?? 0) . "'>" . $firstItem['ghgtmpl_name'] . "</a>" : '' !!} </div>
				</td>
				{{-- Quarter Number --}}
				@if(isset($firstItem[$columnName]) && count($years) > 1)
					@foreach(array_values($firstItem[$columnName]) as $values)
							@for($j = 0; $j < count($years); $j++)
								@php
									try {
										$tco2e=$values['tco2e'][$years[$j]]; 
										$difference=$values['differences'][$years[$j]]; 
									} catch (Exception $e){
										continue;
									}
								@endphp 
								@include('components.reports.tco2e', ['widthCell'=> $widthCell, 'class1' => $class1, 'tco2e' => $tco2e, 'difference' => $difference])
							@endfor
					@endforeach
				@elseif(isset($firstItem[$columnName]) &&  count($years) === 1)
					@php
						$data = $firstItem[$columnName];
						$tco2es = array_map(fn($item) => $item['tco2e'], $data);
					@endphp
					@foreach($tco2es as $key => $values)
								@php
									$tco2e = $values[$years[0]];
									$difference= $data[$key]['differences'][$key];
								@endphp
								@include('components.reports.tco2e', ['widthCell'=> $widthCell, 'class1' => $class1, 'tco2e' => $tco2e, 'difference' => $difference])
					@endforeach
				@else
				<td class='w-{{$widthCell}} {{$class1}} text-right border-t'>
					<div class=''></div>
				</td>
				@endif
				
			</tr>
				@foreach(array_values($remainingItem) as $values3)
			<tr>
				{{--Source--}}
				<td class="{{$class1}} text-left border-t text-base tracking-wide text-blue-800 w-96">
					<div class=''>
						{!! $values3['ghgtmpl_name'] ? "<a href='" . route('ghg_tmpls.edit', $values3['ghg_tmpl_id'] ?? 0) . "'>" . $values3['ghgtmpl_name'] . "</a>" : '' !!} </div>
				</td>
				{{-- Quarter Number --}}
				@if(isset($values3[$columnName]) && count($years) > 1)
					@foreach(array_values($values3[$columnName]) as $values)
						@for($j = 0; $j < count($years); $j++) 
							@php
							try {
								$tco2e=$values['tco2e'][$years[$j]];
								$difference=$values['differences'][$years[$j]];
							} catch (Exception $e){
								continue;
							}
							@endphp 
							@include('components.reports.tco2e', ['widthCell'=> $widthCell, 'class1' => $class1, 'tco2e' => $tco2e, 'difference' => $difference])
						@endfor
					@endforeach
				{{-- Year === 1 --}}
				@elseif(isset($values3[$columnName]) && count($years) === 1)
				@php
						$data = $values3[$columnName];
						$tco2es = array_map(fn($item) => $item['tco2e'], $data);
					@endphp
					@foreach($tco2es as $key => $values)
								@php
									$tco2e = $values[$years[0]];
									$difference= $data[$key]['differences'][$key];
								@endphp
								@include('components.reports.tco2e', ['widthCell'=> $widthCell, 'class1' => $class1, 'tco2e' => $tco2e, 'difference' => $difference])
					@endforeach
				@endif
			</tr>
			@endforeach
			</tr>
			@endforeach
			@endforeach
			<tr class="border-gray-600 border-b">
				{{-- End Row --}}
					@php
					$totalEmissions = array_values($tableDataSource['total_emission']);
					@endphp
					<td class="bg-white border-l border-gray-600" colspan="2"></td>
					<td class=" text-left text-base tracking-wide font-bold">Total Emissions</td>
					@if(count($years) > 1)
						@foreach(array_values($totalEmissions) as $values)
							@for ($i = 0; $i < count($years); $i++)
								@php
									try {
										$tco2e=$values['tco2e'][$years[$i]]; 
										$difference=$values['differences'][$years[$i]]; 
									} catch (\Exception $e){
										continue;
									}
								@endphp
								@include('components.reports.tco2e', ['widthCell'=> $widthCell, 'class1' => $class1, 'tco2e' => $tco2e, 'difference' => $difference, 'fontBold' => 'font-bold'])
							@endfor
						@endforeach
					@elseif(count($years) === 1)
						@php
						$totalEmissions = $tableDataSource['total_emission'];
						$tco2es = array_map(fn($item) => $item['tco2e'], $totalEmissions);
						@endphp
						@foreach($tco2es as $key => $values)
									@php
										$tco2e = $values[$years[0]];
										$difference= $totalEmissions[$key]['differences'][$key];
									@endphp
									@include('components.reports.tco2e', ['widthCell'=> $widthCell, 'class1' => $class1, 'tco2e' => $tco2e, 'difference' => $difference])
						@endforeach
					@else
					@endif
			</tr>
		</tbody>
	</table>
</div>

