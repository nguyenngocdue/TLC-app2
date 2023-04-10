@extends('layouts.app')
@section('topTitle', 'Sign Off')
@section('title', 'Sign Off')
@section('content')
<div class="flex justify-center">
    <div class="md:px-4">
        <div class="w-[1000px] min-h-[1415px] items-center bor1der bg-white box-border p-8">
            <x-sign-off.header-sign-off :dataSource="$headerDataSource" />
            <x-renderer.table maxH="{{false}}" :columns="$tableColumns" :dataSource="$tableDataSource" groupKeepOrder="{{true}}" groupBy="group_description" groupByLength=100 showNo="{{true}}" />
        </div>
    </div>
</div>
@endsection