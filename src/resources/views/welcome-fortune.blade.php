@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.17.0/Sortable.min.css">

<div class="container1 mx-auto my-8">
  <div class="overflow-x-auto whitespace-no-wrap">
    <div class="flex">
      @for($i = 0; $i < 20; $i ++)
      <div class="mx-1 bg-gray-200 p-4 rounded">
        <h2 class="text-lg font-bold mb-1">Column {{$i+1}}</h2>
        <div id="items{{$i}}" class="grid gap-1 w-80">
          <div class="bg-white p-4 shadow rounded">Item 1</div>
          <div class="bg-white p-4 shadow rounded">Item 2</div>
          <div class="bg-white p-4 shadow rounded">Item 3</div>
          <div class="bg-white p-4 shadow rounded">Item 4</div>
          <div class="bg-white p-4 shadow rounded">Item 5</div>
          <div class="bg-white p-4 shadow rounded">Item 6</div>
          <div class="bg-white p-4 shadow rounded">Item 7</div>
          <div class="bg-white p-4 shadow rounded">Item 8</div>
          <div class="bg-white p-4 shadow rounded">Item 9</div>
          <div class="bg-white p-4 shadow rounded">Item 10</div>
        </div>
      </div>
      @endfor
    </div>
    <br/>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
for(let i = 0; i<20;i++){
  var el = document.getElementById('items' + i);
  var sortable = Sortable.create(el,{
    animation: 150,
    group: 'shared',
  });
}
</script>
@endsection