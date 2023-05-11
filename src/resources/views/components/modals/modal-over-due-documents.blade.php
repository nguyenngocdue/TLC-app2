@extends("modals.modal-big")

@php $modalName='modal-over-due-documents'; @endphp

@section($modalName.'-header', "Documents 1")
@section('modal-header-extra')
<input class="bg-pink-100" />
@endsection

@section('modal-body')
<div>
    <x-renderer.emptiness></x-renderer.emptiness>
    <x-renderer.emptiness></x-renderer.emptiness>
    <x-renderer.emptiness></x-renderer.emptiness>
    <x-renderer.emptiness></x-renderer.emptiness>
    <x-renderer.emptiness></x-renderer.emptiness>
    <x-renderer.emptiness></x-renderer.emptiness>
    <x-renderer.emptiness></x-renderer.emptiness>
    <x-renderer.emptiness></x-renderer.emptiness>
    <x-renderer.emptiness></x-renderer.emptiness>
    <x-renderer.emptiness></x-renderer.emptiness>
    <x-renderer.emptiness></x-renderer.emptiness>
    <x-renderer.emptiness></x-renderer.emptiness>
</div>
@endsection

@section('modal-footer')
<div>
    Bye
</div>
@endsection