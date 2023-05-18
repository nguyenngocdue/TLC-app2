@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $title)

@section('content')
<div class="px-4">
    <x-navigation.pill/>
    <form action="{{$route}}" method="POST">
        @csrf
        <x-renderer.table :columns="$columns" :dataSource="$dataSource" :dataHeader="$dataHeader" headerTop="{{$headerTop}}" showNo=true maxH=32 groupBy='name'></x-renderer.table>
        <x-renderer.button type="primary" htmlType='submit' name='button'>Update</x-renderer.button>
    </form>
    <br />
    <hr />
    {{-- <x-form.create-new action="{{$route}}/create"/> --}}
</div>

@php echo $jsStatusArray; @endphp
@php echo $jsStatusArray2; @endphp
@once <script src="{{ asset('js/manage-workflows/toggleHorizonAndVertical.js') }}"></script> @endonce

@endsection