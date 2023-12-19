@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

<div id="divMain"></div>

<script src="{{ asset('js/editable-table.js') }}"></script>
<script>
    
const columns = DataSource.UserColumns
const dataSource = DataSource.Users
$("#divMain").html(EditableTable({columns, dataSource}))
</script>

@endsection 