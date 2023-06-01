@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')
@section('content')


Parent: <x-modals.parent-type7 name='modal_ot_team' selected1='1003-a' allowClear={{true}}></x-modals.parent-type7>
<div class="px-5 bg-white" id="app">
  <div class="grid grid-cols-6">
    <div class="col-span-1">
      <select001></select001>
      <select id="select001" class="w-full">
        <option>AA</option>
        <option>BB</option>
        <option>CC</option>
      </select>
  <script>
    $("#select001").select2()
  </script>
      {{-- Parent: <x-modals.parent-type7 name='modal_ot_team' selected1='1003-a' allowClear={{true}}></x-modals.parent-type7> --}}
      <x-calendar.sidebar-calendar></x-calendar.sidebar-calendar>
      </div>
      <div class="col-span-5">
        <full-calendar></full-calendar>
      </div>
  </div>
</div>
<div class="flex items-center justify-center" class="">
  <a href="https://fullcalendar.io/demos">Demo FullCalender</a>
</div>
<script src="{{ mix('js/app.js') }}" ></script>
@endsection