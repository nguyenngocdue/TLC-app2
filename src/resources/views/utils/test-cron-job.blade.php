@extends('layouts.app')
@section('topTitle', "Test Cron Job")
@section('title', 'Test Cron Job')

@section('content')

<div class='w-full m-10 min-h-screen'>
    <div class="grid grid-cols-12 gap-4 w-3/4 mx-auto">
        <x-renderer.button>AA</x-renderer.button>
        <x-renderer.button>BB</x-renderer.button>
    </div>
</div>

@endsection