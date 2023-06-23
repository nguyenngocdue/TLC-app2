@extends('layouts.app')
@section('content')
    <x-renderer.table showNo={{true}} :dataHeader="$tableDataHeader" :columns="$tableColumns" :dataSource="$tableDataSource"   />
@endsection

