@extends('layouts.app')
@section('title', 'Manage Json')

@section('content')
<x-navigation.pill/>
<form action="{{$route}}" method="POST">
    @csrf
    <x-renderer.table :columns="$columns" :dataSource="$dataSource" showNo=true maxH=32></x-renderer.table>
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
    function toggleVParent(id){
        if(k_checked[id] === undefined){
            k_current[id] = {}
            for(let i = 0; i < statuses.length; i++){
                const x = document.getElementsByName(statuses[i]+'['+id+']')[0]
                if(x !== undefined){
                    k_current[id][statuses[i]] = x.checked
                }
            }
        }
        // console.log(id, statuses, k_current) 123
        k_checked[id] = (k_checked[id] === undefined) ? 0 : (k_checked[id]+1) % 3
        // console.log(k_checked[id]);
        for(let i = 0; i < statuses.length; i++){
            const x = document.getElementsByName(statuses[i]+'['+id+']')[0]
            if(x !== undefined){
                if(k_checked[id] === 0) x.checked = 1
                if(k_checked[id] === 1) x.checked = 0
                if(k_checked[id] === 2) x.checked = k_current[id][statuses[i]]
                // console.log(x)
            }
        }
    }
</script>
@endonce