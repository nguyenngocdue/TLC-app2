@extends('layouts.applean')
@section('content')

<form method="get">
    <textarea rows=25 name='txt'></textarea>
    <x-renderer.button type='success' htmlType='submit'>Parse</x-renderer.button>
</form>

@endsection