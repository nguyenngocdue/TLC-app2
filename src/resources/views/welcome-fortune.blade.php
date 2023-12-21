@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

<div id="divList"></div>
<script src="{{ asset('js/editable-list.js') }}"></script>
<script>EditableListDemo('divList')</script>
<br/>
<br/>
<br/>

<input />
<div id="divSelect"></div>
<input />
<script src="{{ asset('js/editable-select.js') }}"></script>
<script>EditableSelectDemo('divSelect')</script>
{{-- 
<div id="divMain"></div>
<script src="{{ asset('js/editable-table.js') }}"></script>
<script>EditableTableDemo('divMain')</script> --}}

@endsection 