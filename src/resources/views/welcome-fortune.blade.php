@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.17.0/Sortable.min.css">

<div class="container1 mx-auto my-8">
  <div class="overflow-x-auto whitespace-no-wrap">
    <input type="hidden1" id="category" value="{{$category}}" />
    <div class="flex">
      @foreach($columns as $id => $column)
      <div class="mx-1 bg-gray-200 p-4 rounded">
        <h2 class="text-lg font-bold mb-1">{{$column['name'] . " ".$id  }}</h2>
        <div id="{{$id}}" class="grid gap-1 w-80">
          @foreach($column['items'] as $item)
            <div id="{{$item->id}}" class="bg-white p-4 shadow rounded">{{$item->name ?? "???"}}</div>
          @endforeach
        </div>
      </div>
      @endforeach
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