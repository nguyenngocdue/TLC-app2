@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')
<div >
    <x-renderer.table
            showPaginationTop="true"
            :columns="$columns"
            :dataSource="$dataSource"
            :dataHeader="$dataHeader"
            {{-- groupBy="{{$groupBy}}" --}}
            {{-- groupByLength="{{$groupByLength}}" --}}
            {{-- bottomLeftControl="{!! $footer !!}" --}}
            showNo=1

            {{-- topLeftControl="{!!$actionButtons!!}"
            topCenterControl="<div class='flex items-center text-lg'>{!! $tableTopCenterControl !!}</div>"
            topRightControl="{!! $perPage !!}"
            bottomRightControl="{!! $perPage !!}" --}}

            {{-- rotate45Width="{{$rotate45Width}}" --}}
            {{-- tableTrueWidth="{{$tableTrueWidth}}" --}}
            headerTop="{{$headerTop}}"
            />
</div>
@endsection
