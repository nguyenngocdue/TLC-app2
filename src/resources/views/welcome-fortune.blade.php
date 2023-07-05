@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

<div>

  
  <script src="{{ asset('js/go_debug@2.3.8.js') }}"></script>
  HELLO 789
  
  <div id="myDiagramDiv" class="w-full h-screen" style="border: 1px solid black; position: relative; -webkit-tap-highlight-color: rgba(255, 255, 255, 0);">
    <canvas tabindex="0"  111 class="w-full h-screen" style="position: absolute; top: 0px; left: 0px; z-index: 2; user-select: none; touch-action: none;">This text is displayed if your browser does not support the Canvas HTML element.</canvas>
    {{-- <div style="position: absolute; overflow: auto; width: 398px; height: 398px; z-index: 1;">
      <div style="position: absolute; width: 1px; height: 1px;">JS ERROR</div>
    </div> --}}
  </div>
  
  <textarea id="Message1"></textarea>
<script src="{{ asset('js/welcome-fortune.js') }}"></script>

</body>

</div>
@endsection