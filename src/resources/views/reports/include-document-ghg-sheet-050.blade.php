<style type="text/css">
.tg  {
	border-collapse:collapse;
	border-spacing:0;
}
.tg td{
	border-color:black;
	border-style:solid;
	border-width:1px;
	font-family:Arial, sans-serif;
	font-size:14px;
  	overflow:hidden;
	padding:8px;
	word-break:normal;
	background: white;
}
.tg th{
	border-color:black;
	border-style:solid;
	border-width:1px;
	font-family:Arial, sans-serif;
	font-size:14px;
	overflow:hidden;
	padding:8px;
}
.tg td a {
    color: blue;

}

</style>

@php
    //dump($tableDataSource, $complexSettingTable);
	$complexSettingTable = $tableDataSource['tableSetting'];
	$tableDataSource = $tableDataSource['tableDataSource'];
    $totalSpan = $complexSettingTable['total_line'];
    $rowSpanScope1 = $complexSettingTable[335]['scope_rowspan_lv1']+1;
    $rowSpanScope2 = $complexSettingTable[336]['scope_rowspan_lv1'];
    $rowSpanScope3 = $complexSettingTable[337]['scope_rowspan_lv1'];
	$months = $tableDataSource['total_emission'];
@endphp


<table class="tg whitespace-no-wrap w-full text-sm">
	<thead>
	<tr>
		<th class="w-20 {{$class2}}" colspan="2">Category</th>
		<th class="w-[300px] p-2 {{$class2}} border-l ">Emission source category</th>
		<th class="{{$class2}} border-l">Source</th>
		<th class="{{$class2}} border-l">Metric 0</th>
		<th class="{{$class2}} border-l">Metric 1</th>
		<th class="{{$class2}} border-l">Metric 2</th>
		<th class="{{$class2}} border-l">Total </br>(tCO2e)</th>

		{{-- <th class="tg-0pky"><span style="font-style:normal">YTD</span><br>(tCO2e)<br></th> --}}
		@foreach($months as $key => $value)
			@if(is_numeric($key))
				@php
					$strMonth = App\Utils\Support\DateReport::getMonthAbbreviation2((int)$key);
				@endphp
				<th class="p-2 font-bold">{{$strMonth}}</th>
			@endif
		@endforeach
	</tr>
	</thead>
	<tbody>
		<tr>
			<td class="w-20 {{$class1}} text-center border-t" rowspan="{{$totalSpan}}"><span style="font-weight:700;font-style:normal">GHG Protocol Standards: Corporate Scope - 1 and 2, Value Chain - Scope 3</span></td>
		@foreach($complexSettingTable as $k1 => $val1)
				@if(isset($val1['scope_rowspan_lv1']))
					<tr>
						@php
							$rowSpanLv1 = $val1['scope_rowspan_lv1'];
							$scopeName = \App\Models\Term::find($k1)->toArray()['name'];
						@endphp
						<td class="p-2" rowspan="{{$rowSpanLv1 ? $rowSpanLv1 : 1}}">{{$scopeName}}</td>
					{{-- </tr> --}}
					@foreach($val1 as $k2 => $val2)
						@if(isset($val2['scope_rowspan_lv2']))
							@php 
								$rowSpanLv2 = $val2['scope_rowspan_lv2'];
								$ghgcateName = \App\Models\Ghg_cat::find($k2)->toArray()['name'];
							@endphp
							{{-- <tr> --}}
								<td class="p-2" rowspan="{{$rowSpanLv2 ? $rowSpanLv2: 1}}">{{$ghgcateName}}</td>
							@foreach($val2 as $k3 => $val3)
								@if( is_numeric($k3) && isset($val3['scope_rowspan_lv3']))
									@php 
										$rowSpanLv3 = $val3['scope_rowspan_lv3'] ?  $val3['scope_rowspan_lv3'] : 1;
										$ghgTmplName = \App\Models\Ghg_tmpl::find($k3)->toArray()['name'];
										$indexChildrenMetric = $val3['index_children_metric'];
										$childrenMetric = $tableDataSource['scopes'][$k1][$k2][$indexChildrenMetric]['children_metrics'] ?? [];
										//dd($childrenMetric);
									@endphp
									<td class="p-2" rowspan="{{$rowSpanLv3}}">
										{!! $k3 !== '0' ? "<a href='" . route('ghg_tmpls.edit', $k3 ?? 0) . "'>" . $ghgTmplName . "</a>" : '' !!} 
									</td>
									@if(isset($childrenMetric[0]['ghg_tmpls_name']))
									@php
										$months = $childrenMetric[0]['months'] ?? [];
									@endphp
									<td class="p-2">
										@php 
											$idMetricType0 = $childrenMetric[0]['ghg_metric_type_id'] ?? 0;
											$idMetricType1 = $childrenMetric[0]['ghg_metric_type_1_id'] ?? 0;
											$idMetricType2 = $childrenMetric[0]['ghg_metric_type_2_id'] ?? 0;
											$nameMetricType0 = $childrenMetric[0]['ghg_tmpls_name'];
											$nameMetricType1 = $childrenMetric[0]['ghg_metric_type_2_name'];
											$nameMetricType2 = $childrenMetric[0]['ghg_metric_type_2_name'];
										@endphp
										{!! $idMetricType0 !== '0' ? "<a href='" . route('ghg_metric_types.edit', $idMetricType0 ?? 0) . "'>" . $nameMetricType0 . "</a>" : '' !!} 
									</td>
									<td class="p-2">
										{!! $idMetricType1 !== '0' ? "<a href='" . route('ghg_metric_types.edit', $idMetricType1 ?? 0) . "'>" . $nameMetricType1 . "</a>" : '' !!} 
									</td>
									<td class="p-2">
										{!! $idMetricType2 !== '0' ? "<a href='" . route('ghg_metric_types.edit', $idMetricType2 ?? 0) . "'>" . $nameMetricType2 . "</a>" : '' !!} 
									</td>
									<td class="p-2 font-bold">{{$childrenMetric[0]['total_months']}}</td>
										@foreach($months as $month => $valMonth)
											<td class="p-2 text-right">{{$valMonth}}</td>
										@endforeach
									@endif
								</tr>
								{{-- </tr> --}}
									@for($i = 1; $i < $rowSpanLv3; $i++)
										@if(isset($childrenMetric[$i]['ghg_tmpls_name']))
											<tr>
												@php 
													$idMetricType0 = $childrenMetric[$i]['ghg_metric_type_id'] ?? 0;
													$idMetricType1 = $childrenMetric[$i]['ghg_metric_type_1_id'] ?? 0;
													$idMetricType2 = $childrenMetric[$i]['ghg_metric_type_2_id'] ?? 0;
													$nameMetricType0 = $childrenMetric[$i]['ghg_tmpls_name'];
													$nameMetricType1 = $childrenMetric[$i]['ghg_metric_type_1_name'];
													$nameMetricType2 = $childrenMetric[$i]['ghg_metric_type_2_name'];
												@endphp
												<td class="p-2">
													{!! $idMetricType0 !== '0' ? "<a href='" . route('ghg_metric_types.edit', $idMetricType0 ?? 0) . "'>" . $nameMetricType0 . "</a>" : '' !!} 
												</td>
												
												<td class="p-2">
													{!! $idMetricType1 !== '0' ? "<a href='" . route('ghg_metric_types.edit', $idMetricType1 ?? 0) . "'>" . $nameMetricType1 . "</a>" : '' !!} 
												</td>
												<td class="p-2">
													{!! $idMetricType2 !== '0' ? "<a href='" . route('ghg_metric_types.edit', $idMetricType2 ?? 0) . "'>" . $nameMetricType2 . "</a>" : '' !!} 
												</td>
												<td class="p-2 font-bold">{{$childrenMetric[$i]['total_months']}}</td>

												@php
													$months = $childrenMetric[$i]['months'] ?? [];
												@endphp
												@foreach($months as $month => $valMonth)
													<td class="p-2 text-right">{{$valMonth}}</td>
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
	</tbody>
</table>