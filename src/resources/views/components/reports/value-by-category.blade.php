@php
	if(isset($fieldName)) {
		$data =isset($tableDataSource[$fieldName]) && !empty($tableDataSource[$fieldName])? $tableDataSource[$fieldName] : [];
	} else $data = [];
@endphp



@if(!empty($data))
	@if ($timeCategory === 'filter_by_half_year')
			@foreach ( $data as $key => $value )
				@if (str_contains($key, '_range_'))
						<td class="tg-f7v4 bg-green-400">{{$value}}</td>
				@endif
			@endforeach
	@else
		@foreach ( $data as $key => $value )
			@if (str_contains($key, 'by_year_'))
				<td class="tg-f7v4 bg-green-400">{{$value}}</td>
			@endif
		@endforeach
	@endif
@else
	@if ($timeCategory === 'filter_by_half_year')
		@foreach ($titleOfTime as $value)
			<td class="tg-f7v4 ">N/A</td>
		@endforeach
	@else
		@foreach ( $years as $value )
			<td class="tg-f7v4">N/A</td>
		@endforeach
	@endif
@endif