@extends('layouts.applean')
@section('content')

<form method="post" >
    @csrf
    IDs
    <textarea rows=25 name='txtIds'></textarea>
    Values
    <textarea rows=25 name='txtValues'></textarea>
    <x-renderer.button type='success' htmlType='submit'>Parse</x-renderer.button>
</form>

@endsection