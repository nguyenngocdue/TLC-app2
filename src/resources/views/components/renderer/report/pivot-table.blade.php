@extends('layouts.app')
@section('content')
    <x-renderer.table showNo={{true}} :columns="$tableColumns" :dataSource="$tableDataSource"  rotate45Width={{300}} />
@endsection

