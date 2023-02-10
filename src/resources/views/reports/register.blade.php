@extends('layouts.app')

@section('topTitle', 'Reports')
@section('title', 'Manage Workflow')

@section('content')
<form class="w-full mb-8 rounded-lg  dark:bg-gray-800">
    <div class="grid grid-row-1 justify-end">
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-4 w-full">
                <x-renderer.dropdown :dataSource="$subProjects" name="sub_project_id" :itemsSelected="$urlParams"></x-renderer.dropdown>
            </div>
            <div class="col-span-4 w-full">
                <x-renderer.dropdown :dataSource="$chklts_Sheet" name="chklst" :itemsSelected="$urlParams"></x-renderer.dropdown>
            </div>
            <div class="col-span-4 w-full">
                <button type="submit" class="focus:shadow-outline rounded bg-emerald-500 h-full px-4 font-bold text-white hover:bg-purple-400 focus:outline-none">
                    Update
                </button>
            </div>
        </div>
    </div>
    </div>
</form>
<x-renderer.table :columns=" $tableColumns" :dataSource="$tableDataSource" />
@endsection
