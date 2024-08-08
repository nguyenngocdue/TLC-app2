@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', "Orphan many to many")

@section('content')
<form action="{{route('orphanM2M.destroy')}}" method="POST">
    @csrf
    @method('POST')
    <input name="table_name" value="{{$tableFilterOrphan}}" type='hidden' />
        <x-renderer.card title="Orphan Docs">
            <x-renderer.table 
            tableName="orphan_doc"
            :columns="$columns"
            :dataSource="$dataSource1"
            showNo="true"
            editable="true"
            />
        </x-renderer.card>
        <x-renderer.card title="Orphan Terms">
            <x-renderer.table 
            tableName="orphan_term"
            :columns="$columns"
            :dataSource="$dataSource2"
            showNo="true"
            editable="true"
            />
        </x-renderer.card>
        <div class="bg-white rounded-lg mt-2">
            <div class="flex justify-end rounded-lg dark:bg-gray-800 px-5 py-2">
                <x-renderer.button 
                htmlType="submit" type='danger' 
                icon="fa-solid fa-trash" 
                class="border-gray-300" disabled='{{empty($dataSource1) && empty($dataSource2)}}'>Delete</x-renderer.button>
            </div>
        </div>
</form>
@endsection