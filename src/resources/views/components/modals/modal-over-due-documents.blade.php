@extends("modals.modal-big")

@props(['modalId' => md5(now())])

@section('modal-header', "Documents 1")

@section('modal-body')
<div>
    <x-renderer.emptiness></x-renderer.emptiness>
</div>
@endsection

@section('modal-footer')
<div>
    Bye
</div>
@endsection