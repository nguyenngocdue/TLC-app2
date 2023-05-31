@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')
@section('content')
<div class="px-5 bg-white" id="app">
  <div class="grid grid-cols-7">
      <div class="col-span-1">
        <x-calender.sidebar-calender></x-calender.sidebar-calender>
      </div>
      <div class="col-span-6">
        <full-calender></full-calender>
      </div>
  </div>
</div>
<div class="flex items-center justify-center" class="">
  <a href="https://fullcalendar.io/demos">Demo FullCalender</a>
</div>
<script src="{{ mix('js/app.js') }}" ></script>
@endsection