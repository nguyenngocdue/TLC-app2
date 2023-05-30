@extends('layouts.app')
@section('topTitle', "My Company")
@section('title', "Approval Tree")
@section('content')

<x-renderer.card title="" class="border p-2 mx-5 w-1/2 mt-4">
    <form>
        <div class="grid grid-cols-12 w-full">
            <div class="col-span-4 text-right px-2">
                <label for="root_uid" class="justify-end">Root UID</label>
            </div>
            <div class="col-span-8">
                <input id="root_uid" name="root_uid" class="{{$classListText}}" value="{{$_GET['root_uid'] ?? $uid}}">
            </div>
            <div class="col-span-4 text-right px-2">
                <label for="viewport_uid" class="justify-end">Viewport UID</label>
            </div>
            <div class="col-span-8">
                <input id="viewport_uid" name="viewport_uid" class="{{$classListText}}" value="{{$_GET['viewport_uid'] ?? ""}}">
            </div>
            <div class="col-span-4 text-right px-2">
                <label for="leaf_uid" class="justify-end">Leaf UID</label>
            </div>
            <div class="col-span-8">
                <input id="leaf_uid" name="leaf_uid" class="{{$classListText}}" value="{{$_GET['leaf_uid'] ?? ""}}">
            </div>
            <div class="col-span-4 text-right px-2">
                <label for="only_direct_children" class="justify-end">Only Direct Children</label>
            </div>
            <div class="col-span-8">
                <input id="only_direct_children" name="only_direct_children" class="{{$classListCheckbox}}" type="checkbox" @checked($_GET['only_direct_children'] ?? false)>
            </div>
            <div class="col-span-4 text-right px-2">
                <label for="flatten" class="justify-end">Flatten</label>
            </div>
            <div class="col-span-8">
                <input id="flatten" name="flatten" class="{{$classListCheckbox}}" type="checkbox" @checked($_GET['flatten'] ?? false)>
            </div>
        </div>
        <x-renderer.button htmlType="submit" type='success'>Submit</x-renderer.button>
    </form>
    <br/>
    @dump($tree)
</x-renderer.card>

@endsection