@extends('layouts.app')

@section('topTitle',$typeReport)
@section('title', $entity)

{{-- @dump($tableDataSource) --}}


@section('content')

<x-renderer.table-report :dataSource="$sheets"></x-renderer.table-report>
@foreach($tableDataSource as $idSheet => $data)
<x-renderer.table :columns="$tableColumns" :dataSource="$data" groupKeepOrder="{{true}}" groupBy="group_description" groupByLength=100 showNo="{{true}}" />
<br />
<br />
<x-renderer.page-break></x-renderer.page-break>
<br />
<br />
@endforeach
@endsection
