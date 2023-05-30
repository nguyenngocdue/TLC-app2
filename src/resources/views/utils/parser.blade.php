@extends('layouts.applearn')
@section('content')

<form method="post" >
    @csrf
    IDs
    <textarea rows=25 name='txtIds'>1
2</textarea>
    Values
    <textarea rows=25 name='txtValues'>a:1:{i:0;s:6:"Test 1";}
a:1:{i:0;s:6:"Test 2";}</textarea>
    <x-renderer.button type='success' htmlType='submit'>Parse</x-renderer.button>
</form>

@endsection