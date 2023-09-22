@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.17.0/Sortable.min.css">

<div class="container1 mx-auto my-8">
  <div class="overflow-x-auto whitespace-no-wrap">
    <div class="flex">
      @foreach($columns as $id => $column)
      <div class="mx-1 bg-gray-200 p-4 rounded">
        <h2 class="text-lg font-bold mb-1">{{$column['name'] /*. " ".$id */ }}</h2>
        <div id="items{{$id}}" class="grid gap-1 w-80">
          @foreach($column['items'] as $item)
            <div class="bg-white p-4 shadow rounded">{{$item->name ?? "???"}}</div>
          @endforeach
        </div>
      </div>
      @endforeach
    </div>
    <br/>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
const columns = [ {{join(",", array_keys($columns))}} ];
for(let i = 0; i < columns.length; i++){
  const itemName = 'items' + columns[i]
  var el = document.getElementById(itemName);
  // if(!el) {
  //   console.log("NULL")
  //   continue;
  // }
  // console.log(itemName)
  var sortable = Sortable.create(el,{
    animation: 150,
    group: 'shared',
    store:{
      set: (sortable) => {
        var order = sortable.toArray()
        console.log(order, sortable)
      },
    },

    // setData: (dataTransfer, dragEl) => dataTransfer.setData('Text', dragEl.textContent),
    // onChoose: (e) => console.log("onChoose", e, e.oldIndex),
    // onUnchoose: (e) => console.log("onUnchoose", e, e.oldIndex),
    // onStart: (e) => console.log("onStart", e, e.oldIndex),
    // onEnd: (e) => console.log("onEnd", e.item),
    // onAdd: (e) => console.log("onAdd", e.item),
    // onUpdate: (e) => console.log("onUpdate", e.item),
    // onSort: (e) => console.log("onSort", e.item),
    // onRemove: (e) => console.log("onRemove", e.item),
    // onFilter: (e) => console.log("onFilter", e.item),
    // onMove: (e, originalEvent) => console.log("onMove", e.item),
    // onClone: (e) => console.log("onClone", e.item, e.clone),
    // onChange: (e) => console.log("onChange", e.newIndex),
    
  });
}
</script>
@endsection