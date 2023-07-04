@extends('layouts.app')
@section('topTitle', 'Database Diagrams')
@section('title', '')

@section('content')

<script src="{{ asset('js/go_debug@2.3.8.js') }}"></script>

<div id="sample">
    <div id="myDiagramDiv" class="p-2 w-full min-h-[700px]" style="border: 1px solid black; height: 400px; position: relative; -webkit-tap-highlight-color: rgba(255, 255, 255, 0);">
      <canvas tabindex="0" width="398" height="398" style="position: absolute; top: 0px; left: 0px; z-index: 2; user-select: none; touch-action: none; width: 398px; height: 398px;">This text is displayed if your browser does not support the Canvas HTML element.</canvas>
      <div style="position: absolute; overflow: auto; width: 398px; height: 398px; z-index: 1;">
        <div style="position: absolute; width: 1px; height: 1px;">Loading JS...</div>
      </div>
    </div>
  </div>
  <textarea id="mySavedModel" rows="50" class="w-full min-h-fit hidden"></textarea>
  <script>
    var nodeDataArray = @json($nodeDataArray);
    var linkDataArray = @json($linkDataArray);
    </script>
  <script src="{{ asset('js/database-diagrams.js') }}"></script>
@endsection
