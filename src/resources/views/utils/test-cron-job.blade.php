@extends('layouts.app')
@section('topTitle', "Test Cron Job")
@section('title', 'Test Cron Job')

@section('content')

<div class='w-full m-10 min-h-screen'>
    <div class="grid grid-cols-12 gap-4 w-3/4 mx-auto justify-center items-center">
        <x-renderer.button href="?case=sign_off_remind">Sign Off Remind</x-renderer.button>
        <x-renderer.button href="?case=transfer_diginet_data">Transer Diginet Data</x-renderer.button>
    </div>
</div>

@endsection