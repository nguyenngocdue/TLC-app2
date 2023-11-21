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
	$class1 = "bg-white border-gray-600 border-l border-b";
	$class2 =" bg-gray-100 px-4 py-3 border-gray-600 ";

	$complexSettingTable = $tableDataSource['tableSetting'];
	$tableDataSource = $tableDataSource['tableDataSource'];
    $totalSpan = $complexSettingTable['total_line'];
    $rowSpanScope1 = $complexSettingTable[335]['scope_rowspan_lv1']+1;
    $rowSpanScope2 = $complexSettingTable[336]['scope_rowspan_lv1'];
    $rowSpanScope3 = $complexSettingTable[337]['scope_rowspan_lv1'];
	$months = array_keys($tableDataSource['totalEmissionMetricTypeEachMonth']);
@endphp


<div class="rounded-lg border-gray-950 border-2 overflow-hidden">
    <table class="tg whitespace-no-wrap w-full text-sm overflow-hidden border-gray-900">
			<thead>
			<tr>
				<th class="w-20 {{$class2}} border-b" colspan="2">Category</th>
				<th class="w-96 p-2 {{$class2}} border-l border-b">Emission source category</th>
				<th class="w-52 {{$class2}} border-l border-b">Source</th>
				<th class="w-52 {{$class2}} border-l border-b">Metric</th>
				<th class="{{$class2}} border-l border-b">Total </br>(tCO2e)</th>
				{{-- @dd($months) --}}
				@foreach($months as $key => $value)
					@if(is_numeric($key))
						@php
							$strMonth = App\Utils\Support\DateReport::getMonthAbbreviation2((int)$value);
						@endphp
						<th class="p-2 font-bold bg-gray-100 border-l border-gray-600 border-b">{{$strMonth}} <br/>{{$year}}</th>
					@endif
				@endforeach
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
									<td class="p-2 text-center {{$class1}}" rowspan="{{$rowSpanLv1 ? $rowSpanLv1 : 1}}">{{$scopeName}}</td>
								{{-- </tr> --}}
								@foreach($val1 as $k2 => $val2)
									@if(isset($val2['scope_rowspan_lv2']))
										@php 
											$rowSpanLv2 = $val2['scope_rowspan_lv2'];
											$ghgcateName = \App\Models\Ghg_cat::find($k2)->toArray()['name'];
										@endphp
										{{-- <tr> --}}
											<td class="p-2 {{$class1}}"  title="#{{$k2}}" rowspan="{{$rowSpanLv2 ? $rowSpanLv2: 1}}">{{$ghgcateName}}</td>
										@foreach($val2 as $k3 => $val3)
											@if( is_numeric($k3) && isset($val3['scope_rowspan_lv3']))
												@php 
													$rowSpanLv3 = $val3['scope_rowspan_lv3'] ?  $val3['scope_rowspan_lv3'] : 1;
													$ghgTmplName =  \App\Models\Ghg_tmpl::find($k3)->toArray()['name'];
													$ghgTmplName =  \App\Utils\Support\StringReport::removeNumbersAndChars($ghgTmplName);
													$indexChildrenMetric = $val3['index_children_metric'];
													$childrenMetric = $tableDataSource['scopes'][$k1][$k2][$indexChildrenMetric]['group_by_metric0and1'] ?? [];
												@endphp
												<td class="p-2 {{$class1}}" title="#{{$k3}}" rowspan="{{$rowSpanLv3}}">
													{!! $k3 !== '0' ? "<a href='" . route('ghg_tmpls.edit', $k3 ?? 0) . "'>" . $ghgTmplName . "</a>" : '' !!} 
												</td>
												@if(isset($childrenMetric[0]['ghg_tmpls_name']))
												@php
													$months = $childrenMetric[0]['months'] ?? [];
												@endphp
												<td class="p-2 {{$class1}}">
													@php 
														$idMetricType2 = $childrenMetric[0]['ghg_metric_type_2_id'] ?? 0;
														$nameMetricType2 = $childrenMetric[0]['ghg_metric_type_2_name'];
													@endphp
													{!! (float)$idMetricType2 > 0 ? "<a href='" . route('ghg_metric_types.edit', $idMetricType2 ?? 0) . "'>" . $nameMetricType2 . "</a>" : '' !!} 
												</td>
												<td class="p-2 font-bold text-right {{$class1}}">
													{!! (float)$childrenMetric[0]['total_months'] <= 0 ? "<i class='fa-light fa-minus'></i>" : $childrenMetric[0]['total_months'] !!}
												</td>
													@foreach($months as $month => $valMonth)
														<td class="p-2 text-right {{$class1}}">
															{!! (float)$valMonth <= 0 ? "<i class='fa-light fa-minus'></i>" : $valMonth !!}
														</td>
													@endforeach
												{{-- add empty cell --}}
												@elseif(!isset($childrenMetric[0]['ghg_metric_type_id']))
														@if(!isset($childrenMetric[0]['ghg_metric_type_2_id']))
															<td class="text-left {{$class1}}"><i class="fa-light fa-minus"></i></td>
																@php
																	$numOfMonths = count($months);
																@endphp
																@for($i = 0; $i <= $numOfMonths; $i++)
																	<td class="text-center {{$class1}}"><i class="fa-light fa-minus"></i></td>
																@endfor
														@endif
												@endif
											</tr>	
											{{-- </tr> --}}
												@for($i = 1; $i < $rowSpanLv3; $i++)
													@if(isset($childrenMetric[$i]['ghg_tmpls_name']))
														{{-- Metric 0: start second line --}}
														<tr>
															@php 
																$idMetricType2 = $childrenMetric[$i]['ghg_metric_type_2_id'] ?? 0;
																$nameMetricType2 = $childrenMetric[$i]['ghg_metric_type_2_name'];
															@endphp
															
															<td class="p-2 {{$class1}}">
																{!! (float)$idMetricType2 > 0 ? "<a href='" . route('ghg_metric_types.edit', $idMetricType2 ?? 0) . "'>" . $nameMetricType2 . "</a>" : "<i class='fa-light fa-minus'></i>" !!} 
															</td>
															{{-- Total (tCO2e) --}}
															<td class="p-2 font-bold text-right {{$class1}}">
																	{!! (float)$childrenMetric[$i]['total_months'] <= 0 ? "<i class='fa-light fa-minus'></i>" : $childrenMetric[$i]['total_months'] !!}
															</td>
															@php
																$months = $childrenMetric[$i]['months'] ?? [];
															@endphp
															@foreach($months as $month => $valMonth)
																<td class="p-2 text-right {{$class1}}">
																	{!! (float)$valMonth <= 0 ? "<i class='fa-light fa-minus'></i>" : $valMonth !!}
																</td>
															@endforeach
														</tr>				
													@endif
												@endfor
											@endif
										@endforeach
									@endif
								@endforeach
							@endif
					@endforeach
			<tr>
				@php
					$totalEmissionMetricType = $tableDataSource['totalEmissionMetricType'];
					$totalEmissionMetricTypeEachMonth = $tableDataSource['totalEmissionMetricTypeEachMonth'];
				@endphp
				<td class="p-2 text-right font-bold border-l border-t border-gray-600 border-r"  colspan="4">Total Emissions</td>
				<td class="p-2 text-right font-bold text-red-600 border-t border-gray-600"  colspan="1">{{$totalEmissionMetricType}}</td>
				@foreach($totalEmissionMetricTypeEachMonth as $key => $value)
					<td class="p-2 text-right font-bold border-t text-red-600 border-l border-gray-600">{{$value}}</td>
				@endforeach
			</tr>
			</tbody>
		</table>
	</div>
</div>