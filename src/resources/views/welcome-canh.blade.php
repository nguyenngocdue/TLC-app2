@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')
@section('content')
<div class="px-5 bg-white" id="app">
  <div class="grid grid-cols-6">
      <div class="col-span-1">
        <x-calendar.sidebar-calendar></x-calendar.sidebar-calendar>
      </div>
      <div class="col-span-5">
        <x-calendar.full-calendar></x-calendar.full-calendar>
      </div>
  </div>
</div>
<div class="flex items-center justify-center" class="">
  <a href="https://fullcalendar.io/demos">Demo FullCalender</a>
</div>
<script src="{{ mix('js/app.js') }}" ></script>
@endsection