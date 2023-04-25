@extends('layouts.app')
@section('topTitle', $topTitle)
@section('title', 'Sign Off')
@section('content')
<div class="flex justify-center">
    <div class="md:px-4">
        <div class="w-[1000px] min-h-[1415px] items-center bg-white box-border p-8">
                <x-print.table-of-contents :dataSource="$headerDataSource" :headerDataSource="$entityDataSource" type="{{$type}}"/>
                @foreach($tableDataSource as $key => $value)
                <x-print.header5 :dataSource="$headerDataSource[$key]" page='{{$key+1}}' />
                <x-renderer.table maxH="{{false}}" :columns="$tableColumns" :dataSource="$value" groupKeepOrder="{{true}}" groupBy="group_description" groupByLength=100 showNo="{{true}}" />
                <x-renderer.page-break />
                @endforeach
            </div>
    </div>
</div>
@endsection