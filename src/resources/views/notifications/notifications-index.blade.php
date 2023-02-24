@extends('layouts.app')

@section('topTitle', 'Notifications')
@section('title', '')

@section('content')
<div class="px-10 py-1">
    <x-renderer.all-notifications :dataSource="$dataSource" showAll={{true}}/>
</div>
@endsection
