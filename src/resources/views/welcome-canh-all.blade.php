@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')
@section('content')
<div class="mt-5">
  <x-renderer.calendar-view-all  />
</div>
@endsection