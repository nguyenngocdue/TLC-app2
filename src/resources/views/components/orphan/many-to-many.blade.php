@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', "Orphan many to many")

@section('content')
<form action="{{route('orphan.destroy')}}" method="POST">
    @csrf
    @method('POST')
    @empty(!$dataSource1)
        <x-renderer.card title="Doc Orphan">
            <x-renderer.table 
            tableName="orphan_doc"
            showPaginationTop="true"
            :columns="$columns"
            :dataSource="$dataSource1"
            />
        </x-renderer.card>
        
    @endempty
    @empty(!$dataSource2)
        <x-renderer.card title="Doc Orphan">
            <x-renderer.table 
            tableName="orphan_term"
            showPaginationTop="true"
            :columns="$columns"
            :dataSource="$dataSource2"
            />
        </x-renderer.card>
    @endempty
    <div class="bg-white rounded-lg mt-2">
        <div class="flex justify-end rounded-lg dark:bg-gray-800 px-5 py-2">
            <x-renderer.button htmlType="submit" type='danger' icon="fa-solid fa-trash"
                    class="border-gray-300"
                    >Delete</x-renderer.button>
        </div>
    </div>
</form>
@endsection