@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

{{-- <input />
<div id="divSelect"></div>
<input />
<script src="{{ asset('js/editable-select.js') }}"></script>
<script>EditableSelectDemo('divSelect')</script>

<br/>
<br/>
<br/>

<div id="divList"></div>
<script src="{{ asset('js/editable-list.js') }}"></script>
<script>EditableListDemo('divList')</script> --}}


<div id="divMain"></div>
<script src="{{ asset('js/editable-table.js') }}"></script>
<script>EditableTableDemo('divMain')</script>

@endsection 