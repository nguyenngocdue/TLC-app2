@extends('layouts.applean')
@section('content')

<div>
    <x-renderer.heading level=3 align='center'><a href="/components">Welcome Fortune</a></x-renderer.heading>
    <x-modals.parent-type7-user-ot name='ot_team'></x-modals.parent-type7>
    {{-- <x-modals.parent-id7-user-ot name='ot_user1' multiple={{false}} control='radio-or-checkbox2'></x-modals.parent-type7> --}}
    <x-modals.parent-id7-user-ot name='ot_user2' multiple={{true}} control='radio-or-checkbox2'></x-modals.parent-type7>
    {{-- <x-modals.parent-id7-user-ot name='ot_user3' multiple={{false}}></x-modals.parent-type7> --}}
    <x-modals.parent-id7-user-ot name='ot_user4' multiple={{true}}></x-modals.parent-type7>
</div>
@endsection
