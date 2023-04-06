@extends('layouts.applean')
@section('content')

<form>
    <div class="grid grid-cols-12 w-1/2">
        <div class="col-span-6">
            <label for="root_uid">Root UID</label>
        </div>
        <div class="col-span-6">
            <input id="root_uid" name="root_uid" class="{{$classListText}}" value="{{$_GET['root_uid'] ?? ""}}">
        </div>
        <div class="col-span-6">
            <label for="viewport_uid">Viewport UID</label>
        </div>
        <div class="col-span-6">
            <input id="viewport_uid" name="viewport_uid" class="{{$classListText}}" value="{{$_GET['viewport_uid'] ?? ""}}">
        </div>
        <div class="col-span-6">
            <label for="leaf_uid">Leaf UID</label>
        </div>
        <div class="col-span-6">
            <input id="leaf_uid" name="leaf_uid" class="{{$classListText}}" value="{{$_GET['leaf_uid'] ?? ""}}">
        </div>
        <div class="col-span-6">
            <label for="only_direct_children">Only Direct Children</label>
        </div>
        <div class="col-span-6">
            <input id="only_direct_children" name="only_direct_children" class="{{$classListCheckbox}}" type="checkbox" @checked($_GET['only_direct_children'] ?? false)>
        </div>
        <div class="col-span-6">
            <label for="flatten">Flatten</label>
        </div>
        <div class="col-span-6">
            <input id="flatten" name="flatten" class="{{$classListCheckbox}}" type="checkbox" @checked($_GET['flatten'] ?? false)>
        </div>
    </div>
    <x-renderer.button htmlType="submit">Submit</x-renderer.button>
</form>

@dump($tree)

@endsection