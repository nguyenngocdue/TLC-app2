@extends('layouts.app')

@section('topTitle',$typeReport)
@section('title', $entity)
{{-- @dump($tableDataSource, $tableColumns) --}}
{{-- @dump($typeReport) --}}
{{-- reports/register-hr_overtime_request_line --}}
@section('content')
<div class="px-4 ">
    <div class="flex justify-end pb-2 pr-4">
        <x-form.per-page type="{{$typeReport}}" route="{{ route('updateUserSettings') }}" />
    </div>
    <x-renderer.parameter-control :dataSource="$dataModeControl" :itemsSelected="$urlParams" />
    <x-renderer.table maxH="{{false}}" :columns="$tableColumns" :dataSource="$tableDataSource" showNo={{true}} rotate45Width={{600}}/>
</div>
@endsection
