@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

<div id="divMain"></div>

<script src="{{ asset('js/editable-table.js') }}"></script>
<script>

$("#divMain").html(EditableTable())
</script>

@endsection 