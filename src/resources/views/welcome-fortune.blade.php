@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

<div id="divSelect"></div>
<script src="{{ asset('js/editable-select.js') }}"></script>
<script>EditableSelectDemo('divSelect')</script>

<div id="divMain"></div>
<script src="{{ asset('js/editable-table.js') }}"></script>
<script>EditableTableDemo('divMain')</script>

@endsection 