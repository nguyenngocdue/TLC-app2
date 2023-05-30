@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')
@section('content')
<div class="p-10 bg-white" id="app">
  <full-calender></full-calender>
</div>
<script src="{{ mix('js/app.js') }}" ></script>
@endsection