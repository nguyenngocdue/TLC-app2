@php
	if(isset($fieldName)) {
		$data =isset($tableDataSource[$fieldName]) && !empty($tableDataSource[$fieldName])? $tableDataSource[$fieldName][0] : [];
	} else{
		$data = [];
	}
@endphp
@if ($timeCategory === 'filter_by_hafl_year')
	@if(!empty($data))
		@foreach ( $data as $key => $value )
		@if (str_contains($key, '_range_'))
				<td class="tg-f7v4">{{$value}}</td>
		@endif
		@endforeach
	@endif
@endif