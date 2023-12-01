<style type="text/css">
.tg  {
	border-collapse:collapse;
	border-spacing:0;
}
.tg td{
	padding:8px;
	background: white;
}
.tg th {
    padding: 8px;
}
.tg td a {
    color: blue;
}

</style>
@php
	$widthCell = 88;
	$class1 = "bg-white border-gray-600 border-l border-b border-t";
	$class2 =" bg-gray-100 px-4 py-3 border-gray-600 ";
	$complexSettingTable = $tableDataSource['tableSetting'];
	$tableData = $tableDataSource['tableDataSource'];
    $totalSpan = $complexSettingTable['total_line'];
    $rowSpanScope1 = $complexSettingTable[335]['scope_rowspan_lv1']+1;
    $rowSpanScope2 = $complexSettingTable[336]['scope_rowspan_lv1'];
    $rowSpanScope3 = $complexSettingTable[337]['scope_rowspan_lv1'];
	$months = $tableDataSource['timeInfo']['months']?? [];
	$years = $tableDataSource['timeInfo']['years'];
	$columnType = $tableDataSource['timeInfo']['columnType'];
	$dataSet = $tableDataSource['dataSet'];
	#dump($tableData['scopes'][337]);
@endphp
{{-- @dd($columnType) --}}
<div class="rounded-lg border-gray-950 border-2 overflow-hidden">
    <table class="tg whitespace-no-wrap w-full text-sm overflow-hidden border-gray-900">
		<thead>
		<tr class="rounded-t-lg bg-gray-100 border">
			<th class="w-20 {{$class2}} border-b" colspan="2">Category</th>
			<th class="w-[300px] p-2 {{$class2}} border-l border-b">Emission source category</th>
			<th class="w-20 {{$class2}} border-l  border-b">Source</th>
			<th class="w-20 {{$class2}} border-l  border-b">Metric 0</th>
			<th class="w-20 {{$class2}} border-l  border-b">Metric 1</th>
			<th class="w-20 {{$class2}} border-l  border-b">Metric 2</th>
			@switch($columnType)
				@case("years")
					<th id="" colspan="{{count($years)}}" class=" border-l bg-gray-100 py-2 border-gray-600 border-b">
							<div class="text-gray-700 dark:border-gray-600 ">
								<span>Year </br><p class="font-normal">(tCO2e)</p></span>
							</div>
						</th>
					@break
				@case("months" || "quarters")
						@foreach($months as $month)
						<th id="" colspan="{{count($years)}}" class=" border-l bg-gray-100 py-2 border-gray-600 border-b">
							<div class="text-gray-700 dark:border-gray-600 ">
									@php
										$value = App\Utils\Support\DateReport::getMonthAbbreviation($month);
									@endphp
								<span>
										{{$value}}</br><p class='font-normal'>(tCO2e)</p>
								</span>
							</div>
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
				<th class="border-gray-600 border-b"></th>
				<th class="border-gray-600 border-b"></th>
				<th class="border-gray-600 border-b"></th>
				<th class="border-gray-600 border-b"></th>
				<th class="border-gray-600 border-b"></th>
				<th class="border-gray-600 border-b"></th>
				<th class="border-gray-600 border-b"></th>
				@switch($columnType)
					@case("years")
						@foreach($years as $year)
							<th class=" bg-gray-100 px-4 py-3 border-gray-600 border-l border-t text-base tracking-wide">{{$year}}</th>
						@endforeach
						@break
					@case("months" || "quarters")
							@foreach($months as $month)
								@foreach($years as $year)
									<th class=" bg-gray-100 px-4 py-3 border-gray-600 border-l border-t border-b text-base tracking-wide">{{$year}}</th>
								@endforeach
							@endforeach								
						@break
				@default
				@break    
			@endswitch
			</tr>
		</thead>

		<tbody>
			<tr>
				<td class="w-20 text-center border-r border-gray-600" rowspan="{{$totalSpan}}"><span style="font-weight:700;font-style:normal">GHG Protocol Standards: Corporate Scope - 1 and 2, Value Chain - Scope 3</span></td>
				@foreach($complexSettingTable as $k1 => $val1)
						@if(isset($val1['scope_rowspan_lv1']))
							<tr>
								@php
									$rowSpanLv1 = $val1['scope_rowspan_lv1'];
									$scopeName = \App\Models\Term::find($k1)->toArray()['name'];
								@endphp
								<td class="p-2 text-center border-b border-gray-600" rowspan="{{$rowSpanLv1 ? $rowSpanLv1 : 1}}">{{$scopeName}}</td>
							{{-- </tr> --}}
							@foreach($val1 as $k2 => $val2)
								@if(isset($val2['scope_rowspan_lv2']))
									@php 
										$rowSpanLv2 = $val2['scope_rowspan_lv2'];
										$ghgcateName = \App\Models\Ghg_cat::find($k2)->toArray()['name'];
									@endphp
									{{-- <tr> --}}
									{{-- Emission source category --}}
										<td class="p-2 {{$class1}}"  title="#{{$k2}}" rowspan="{{$rowSpanLv2 ? $rowSpanLv2: 1}}">{{$ghgcateName}}</td>
									@foreach($val2 as $k3 => $val3)
										@if( is_numeric($k3) && isset($val3['scope_rowspan_lv3']))
											@php 
												$rowSpanLv3 = $val3['scope_rowspan_lv3'] ?  $val3['scope_rowspan_lv3'] : 1;
												$ghgTmplName =  \App\Models\Ghg_tmpl::find($k3)->toArray()['name'];
												$ghgTmplName =  \App\Utils\Support\StringReport::removeNumbersAndChars($ghgTmplName);
												$indexChildrenMetric = $val3['index_children_metric'];
												$childrenMetric = $tableData['scopes'][$k1][$k2][$indexChildrenMetric]['children_metrics'] ?? [];
												//dump($childrenMetric);
											@endphp
											<td class="p-2 {{$class1}}" title="#{{$k3}}" rowspan="{{$rowSpanLv3}}">
												{!! $k3 !== '0' ? "<a href='" . route('ghg_tmpls.edit', $k3 ?? 0) . "'>" . $ghgTmplName . "</a>" : '' !!} 
											</td>
											@if(isset($childrenMetric[0]['ghg_tmpls_name']))
											{{-- Metric 0: first line --}}
											<td class="p-2 {{$class1}}">
												@php 
													$idMetricType0 = $childrenMetric[0]['ghg_metric_type_id'] ?? 0;
													$idMetricType1 = $childrenMetric[0]['ghg_metric_type_1_id'] ?? 0;
													$idMetricType2 = $childrenMetric[0]['ghg_metric_type_2_id'] ?? 0;
													$nameMetricType0 = $childrenMetric[0]['ghg_tmpls_name'];
													$nameMetricType0 =  \App\Utils\Support\StringReport::removeNumbersAndChars($nameMetricType0);
													$nameMetricType1 = $childrenMetric[0]['ghg_metric_type_2_name'];
													$nameMetricType2 = $childrenMetric[0]['ghg_metric_type_2_name'];
												@endphp
												{!! (float)$idMetricType0 > 0 ? "<a href='" . route('ghg_metric_types.edit', $idMetricType0 ?? 0) . "'>" . $nameMetricType0 . "</a>" : '' !!} 
											</td>
											<td class="p-2 {{$class1}}">
												{!! (float)$idMetricType1  > 0 ? "<a href='" . route('ghg_metric_types.edit', $idMetricType1 ?? 0) . "'>" . $nameMetricType1 . "</a>" : '' !!} 
											</td>
											<td class="p-2 {{$class1}}">
												{!! (float)$idMetricType2 > 0 ? "<a href='" . route('ghg_metric_types.edit', $idMetricType2 ?? 0) . "'>" . $nameMetricType2 . "</a>" : '' !!} 
											</td>
											{{-- Months --}}
												@switch($columnType)
													@case('years')
															@foreach($years as $key => $year)
																@php
																	//dd($childrenMetric);
																	$arr = $dataSet[$k1][$k2][$year] ?? [];
																	$itemsToCheck = $arr[$k2][$k3];
																	$standardItem = $childrenMetric[0];

																	//indexItem
																	$dataIndex =  App\Utils\Support\Report::checkItem($itemsToCheck, $standardItem);
																	$valueForColType = empty($dataIndex) ? '' : $dataIndex['data_render'][$year][$columnType];
																	$numberComparison = empty($dataIndex) ? null :  $dataIndex['comparison_with']["meta_percent"][$columnType];
																	$numberComparison = (float)$numberComparison === (float)0 ? null : $numberComparison;
																	//dd($dataIndex);
																@endphp
																	@include('components.reports.tco2e', ['widthCell'=> '', 'class1' => '', 'tco2e' => $valueForColType, 'difference' => $numberComparison])
															@endforeach
														@break
													@case ('months' || 'quarters')
															@php
																$dataIndex = $dataSet[$k1][$k2];
															@endphp
															@foreach($months as $month)
																@php
																	$month = str_pad($month, 2, '0', STR_PAD_LEFT);
																@endphp
																	@foreach($years as $year)
																		@php
																			$arr = $dataSet[$k1][$k2][$year];
																			$itemsToCheck = $arr[$k2][$k3] ?? [];
																			$standardItem = $childrenMetric[0];

																			//indexItem
																			$dataIndex =  App\Utils\Support\Report::checkItem($itemsToCheck, $standardItem) ?? [];
																			$valueForColType = empty($dataIndex) ? '' : $dataIndex['data_render'][$year][$columnType][$month];
																			$numberComparison = empty($dataIndex) ? null :  ($dataIndex['comparison_with']["meta_percent"][$columnType][$month] ?? null);
																			$numberComparison = (float)$numberComparison === (float)0 ? null : $numberComparison;
																		@endphp
																			@include('components.reports.tco2e', ['widthCell'=> '', 'class1' => '', 'tco2e' => $valueForColType, 'difference' => $numberComparison])
																	@endforeach
															@endforeach

														@break

													@default
														@break	
												@endswitch

											
											{{-- add empty cell --}}
											@elseif(!isset($childrenMetric[0]['ghg_metric_type_id']))
													<td class="text-left {{$class1}}"><i class="fa-light fa-minus"></i></td>
												@if(!isset($childrenMetric[0]['ghg_metric_type_1_id']))
														<td class="text-left {{$class1}}"><i class="fa-light fa-minus"></i></td>
													@if(!isset($childrenMetric[0]['ghg_metric_type_2_id']))
														<td class="text-left {{$class1}}"><i class="fa-light fa-minus"></i></td>
															@php
																$numOfMonths = count($months);
															@endphp
															@for($i = 1; $i <= $numOfMonths; $i++)
																<td class="text-center {{$class1}}"><i class="fa-light fa-minus"></i></td>
															@endfor
													@endif
												@endif
											@endif
										</tr>	
										{{-- </tr> --}}
											@for($i = 1; $i < $rowSpanLv3; $i++)
												@if(isset($childrenMetric[$i]['ghg_tmpls_name']))
													{{-- Metric 0: start second line --}}
													<tr>
														@php 
															$idMetricType0 = $childrenMetric[$i]['ghg_metric_type_id'] ?? 0;
															$idMetricType1 = $childrenMetric[$i]['ghg_metric_type_1_id'] ?? 0;
															$idMetricType2 = $childrenMetric[$i]['ghg_metric_type_2_id'] ?? 0;
															$nameMetricType0 = $childrenMetric[$i]['ghg_tmpls_name'];
															$nameMetricType0 =  \App\Utils\Support\StringReport::removeNumbersAndChars($nameMetricType0);
															$nameMetricType1 = $childrenMetric[$i]['ghg_metric_type_1_name'];
															$nameMetricType2 = $childrenMetric[$i]['ghg_metric_type_2_name'];
														@endphp
														<td class="p-2 {{$class1}}">
															{!! (float)$idMetricType0 > 0 ? "<a href='" . route('ghg_metric_types.edit', $idMetricType0 ?? 0) . "'>" . $nameMetricType0 . "</a>" : "<i class='fa-light fa-minus'></i>" !!} 
														</td>
														
														<td class="p-2 {{$class1}}">
															{!! (float)$idMetricType1 > 0 ? "<a href='" . route('ghg_metric_types.edit', $idMetricType1 ?? 0) . "'>" . $nameMetricType1 . "</a>" : "<i class='fa-light fa-minus'></i>" !!} 
														</td>
														<td class="p-2 {{$class1}}">
															{!! (float)$idMetricType2 > 0 ? "<a href='" . route('ghg_metric_types.edit', $idMetricType2 ?? 0) . "'>" . $nameMetricType2 . "</a>" : "<i class='fa-light fa-minus'></i>" !!} 
														</td>
														{{-- Months --}}
														@switch($columnType)
															@case('years')
																	@foreach($years as $key => $year)
																		@php
																			$arr = $dataSet[$k1][$k2][$year] ?? [];
																			$valueForColType = $arr[$k2][$k3][$i]['data_render'][$year][$columnType]  ?? 0;

																			$arr = $dataSet[$k1][$k2][$year];
																			$itemsToCheck = $arr[$k2][$k3];
																			$standardItem = $childrenMetric[$i];
																			
																			//indexItem
																			$dataIndex =  App\Utils\Support\Report::checkItem($itemsToCheck, $standardItem);
																			$valueForColType = empty($dataIndex) ? '' : $dataIndex['data_render'][$year][$columnType];
																			$numberComparison = empty($dataIndex) ? null :  $dataIndex['comparison_with']["meta_percent"][$columnType];
																			$numberComparison = (float)$numberComparison === (float)0 ? null : $numberComparison;
																		@endphp
																			@include('components.reports.tco2e', ['widthCell'=> '', 'class1' => '', 'tco2e' => $valueForColType, 'difference' => $numberComparison])
																	@endforeach
																@break
															@case('months' || 'quarters')
																	@php
																		$dataIndex = $dataSet[$k1][$k2];
																	@endphp
																	@foreach($months as $month)
																		@php
																			$month = str_pad($month, 2, '0', STR_PAD_LEFT);
																		@endphp
																			@foreach($years as $year)
																				@php
																					$arr = $dataSet[$k1][$k2][$year] ?? [];
																					$valueForColType = $arr[$k2][$k3][$i]['data_render'][$year][$columnType]  ?? 0;

																					$arr = $dataSet[$k1][$k2][$year];
																					$itemsToCheck = $arr[$k2][$k3] ?? [];
																					$standardItem = $childrenMetric[$i];
																					//indexItem
																					$dataIndex =  App\Utils\Support\Report::checkItem($itemsToCheck, $standardItem);
																					$valueForColType = empty($dataIndex) ? '' : $dataIndex['data_render'][$year][$columnType][$month];
																					$numberComparison = empty($dataIndex) ? null :  ($dataIndex['comparison_with']["meta_percent"][$columnType][$month] ?? null);
																					$numberComparison = (float)$numberComparison === (float)0 ? null : $numberComparison;
																				@endphp
																					@include('components.reports.tco2e', ['widthCell'=> '', 'class1' => '', 'tco2e' => $valueForColType, 'difference' => $numberComparison])
																			@endforeach
																	@endforeach
																@break
															@default
																@break
																
														@endswitch
													</tr>
												@endif
											@endfor
										@endif
									@endforeach
								@endif
							@endforeach
						@endif
				@endforeach
			</tr>
		
		<tr>
		@php
			$infoSummaryAllColumn = $tableDataSource['infoSummaryAllColumn'][$columnType];
			$dataRender = $infoSummaryAllColumn['data_render'];
			$comparison = $infoSummaryAllColumn['comparison_with'];
		@endphp
			<td class="bg-white border-l border-gray-600 text-center text-base tracking-wide font-bold" " colspan="5">Total Emissions</td>
			@switch($columnType)
				@case('years')
					@foreach($years as $year) 
						@php
							$tco2e  = (float)$dataRender[$year] === (float)0 ? null: $dataRender[$year];
							$difference= (float)$comparison[$year] === (float)0 ? null: $comparison[$year];
						@endphp
						@include('components.reports.tco2e', ['widthCell'=> $widthCell, 'class1' => $class1, 'tco2e' => $tco2e, 'difference' => $difference])
					@endforeach
					@break
				@case('months' || 'quarter')
					@foreach($months as $month)
						@foreach($years as $year)
							@php
								$tco2e  = (float)$dataRender[$month][$year] === (float)0 ? null: $dataRender[$month][$year];
								$difference= (float)$comparison[$month][$year] === (float)0 ? null: $comparison[$month][$year];
							@endphp
							@include('components.reports.tco2e', ['widthCell'=> $widthCell, 'class1' => $class1, 'tco2e' => $tco2e, 'difference' => $difference, 'fontBold'=> 'font-bold'])
						@endforeach
					@endforeach
					@break
				@default
					@break
			@endswitch
		</tr>
		</tbody>
	</table>
</div>
