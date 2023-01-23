@extends('layouts.app')

@section('topTitle', Str::headline(Str::plural($type)))
@section('title', 'Manage Workflow')

@section('content')
<x-navigation.pill/>
<form action="{{$route}}" method="POST">
    @csrf
    <x-renderer.table :columns="$columns" :dataSource="$dataSource" :dataHeader="$dataHeader" showNo=true maxH=32 headerTop={{$headerTop}}></x-renderer.table>
    <x-renderer.button type="primary" htmlType='submit' name='button'>Update</x-renderer.button>
</form>
<br />
<hr />
{{-- <x-form.create-new action="{{$route}}/create"/> --}}
<br />
<br />
<br />
@endsection

@once
<script>
    let toggleVParentMode = "";
    const getCheckboxElement = (status, id) => document.getElementsByName(status+'['+id+']')[0]

    function storeMatrixToVariable(){
        k_horizon_mode = {}
        k_vertical_mode = {}
        k_horizon_value = {}
        k_vertical_value = {}
        for(let i = 0; i < statuses.length; i++){
            for(let j = 0; j < ids.length; j ++){
                const status = statuses[i]
                const id = ids[j]
                const x = getCheckboxElement(status, id)
                if(x !== undefined) {
                    if(k_horizon_value[id] === undefined) k_horizon_value[id] = {}
                    k_horizon_value[id][status] = x.checked
                    if(k_vertical_value[status] === undefined) k_vertical_value[status] = {}
                    k_vertical_value[status][id] = x.checked
                }
            }
        }
    }

    function toggleVParent_Horizon(id){
        if(toggleVParentMode !== 'horizon') {
            toggleVParentMode = "horizon"
            storeMatrixToVariable()
        }
        k_horizon_mode[id] = (k_horizon_mode[id] === undefined) ? 0 : (k_horizon_mode[id] + 1) % 3
        for(let i = 0; i < statuses.length; i++){
            const x = getCheckboxElement(statuses[i], id)
            if(x !== undefined){
                if(k_horizon_mode[id] == 0) x.checked = 1
                if(k_horizon_mode[id] == 1) x.checked = 0
                if(k_horizon_mode[id] == 2) x.checked = k_horizon_value[id][statuses[i]]
            }
        }
    }

    function toggleVParent_Vertical(status){
        if(toggleVParentMode !== 'vertical'){
            toggleVParentMode = "vertical"
            storeMatrixToVariable()
        }
        k_vertical_mode[status] = (k_vertical_mode[status] === undefined) ? 0 : (k_vertical_mode[status] + 1 ) % 3
        for(let i = 0; i < ids.length; i++){
            const x = getCheckboxElement(status, ids[i])
            if(x !== undefined){
                if(k_vertical_mode[status] == 0) x.checked = 1
                if(k_vertical_mode[status] == 1) x.checked = 0
                if(k_vertical_mode[status] == 2) x.checked = k_vertical_value[status][ids[i]]
            }
        }
    }
</script>
@endonce