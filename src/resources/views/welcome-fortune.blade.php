@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.17.0/Sortable.min.css">

<input type="hidden" id="category" value="{{$category}}" />
<div class="container1 mx-auto my-8">
  <div class="overflow-x-auto whitespace-no-wrap">
    
    <div id="wrapper_0">
    <div class="cursor-grab">
      <h2>Row 0</h2>
      <div id="row_0" class="flex bg-gray-50 m-2 border rounded cursor-grab p-2">
        
        @foreach($columns as $id => $column)
        <div class="m-1 bg-gray-200 p-2 rounded">
          <h2 class="text-xs font-bold mb-1">{{$column['name'] . " ".$id  }}</h2>
          <div id="column_{{$id}}" class="grid gap-1 w-60">
            {{-- column_{{$id}} --}}
            @foreach($column['items'] as $item)
            <div id="item_{{$item->id}}" class="bg-white p-1 shadow rounded text-xs">{{$item->name ?? "???"}} item_{{$item->id}}</div>
            @endforeach
          </div>
        </div>
        @endforeach
      </div>
    </div>
    
    <div class="cursor-grab"><h2>Row 1 </h2>
      <div id="row_1" class="flex bg-gray-50 m-2 border rounded cursor-grab p-2">
        
      </div>
    </div>
    <div class="cursor-grab"><h2>Row 2</h2>
      <div id="row_2" class="flex bg-gray-50 m-2 border rounded cursor-grab p-2">
        
      </div>
    </div>
  </div>
  <br/>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="{{ asset('js/kanban.js') }}"></script>
<script>
const columns = [ {{join(",", array_keys($columns))}} ];
const route = "{{$route}}";
kanbanInit(columns, route)
</script>
@endsection