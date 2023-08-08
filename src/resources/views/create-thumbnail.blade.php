@extends('layouts.app')
@section('topTitle', 'Thumbnails')
@section('title', 'Create Thumbnails')

@section('content')
<form action="{{route('createThumbnail.create')}}" method="POST">
    <div class="px-4 mt-2 mx-96 space-y-4">
        @csrf
        @method('POST')
        <div>
            <label for="height">Height</label>
            <x-controls.number2 name="height" value="{{$settings['height'] ?? ''}}" ></x-renderer.number2>
        </div>
        <div>
            <label for="width">Width</label>
            <x-controls.number2 name="width" value="{{$settings['width'] ?? ''}}" ></x-renderer.number2>
        </div>
        <div>
            <label for="position">Position</label>
            <x-renderer.editable.dropdown name="position" :cbbDataSource='["top", "center","bottom"]' cell="{{$settings['position']}}" ></x-renderer.editable.dropdown>
        </div>
        <div class="flex justify-end">
            <x-renderer.button htmlType="submit" type="primary">Create</x-renderer.button>
        </div>
    </div>
</form>

@endsection
