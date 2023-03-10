@extends('layouts.applean')
@section('content')

<div>
    <x-renderer.heading level=3 align='center'><a href="/components">Welcome Fortune</a></x-renderer.heading>
    {{-- @dump($teamList) --}}
    {{-- <x-controls.has-data-source.dropdown2 type='user_team_ot' name=''></x-controls.has-data-source.dropdown2> --}}
    {{-- <x-renderer.dropdown title="A" name="name" :dataSource="$dataSource" $allowClear="0" :itemsSelected="$itemsSelected" ></x-renderer.dropdown> --}}
</div>
@endsection
