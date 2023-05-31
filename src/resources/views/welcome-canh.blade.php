@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')
@section('content')
<div class="px-5 bg-white" id="app">
  <full-calender></full-calender>
</div>
<script src="{{ mix('js/app.js') }}" ></script>
@endsection