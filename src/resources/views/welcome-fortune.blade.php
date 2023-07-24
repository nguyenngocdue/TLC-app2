@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

<div>

  <form method="post" id="abc">
    <select name='select1' readonly>
      <option>Optoin 1</option>
      <option selected>Optoin 2</option>
      <option>Optoin 3</option>
      <option>Optoin 4</option>
    </select>
    <select name='select2' multiple readonly>
      <option>Optoin 1</option>
      <option selected >Optoin 2</option>
      <option>Optoin 3</option>
      <option selected>Optoin 4</option>
    </select>
    <input type="submit" value="post form" />
  </form>
  
  <button id="setreadonly">
    Make Readonly
  </button>
  <button id="removereadonly">
    Remove Readonly
  </button>

  <script>
    var $S1 = $("select[name=select1]");
    var $S2 = $("select[name=select2]");
  
  $('select').select2({
	  width: '100%'
	});

	function readonly_select(objs, action) {
	  if (action === true)
	    objs.prepend('<div class="disabled-select"></div>');
	  else
	    $(".disabled-select", objs).remove();
	}
	$('#setreadonly').on('click', function() {
	  //readonly_select($(".select2"), true);

	  $S1.attr("readonly", "readonly");
   
		$S2.attr("readonly", "readonly");

	});
	$('#removereadonly').on('click', function() {
	  //readonly_select($(".select2"), false);
    
		$S1.removeAttr('readonly');
    $S2.removeAttr('readonly');

	});
	$("#abc").on('submit', function() {
	  alert($("#abc").serialize());
	});
  </script>
  
  {{-- <script src="{{ asset('js/go_debug@2.3.8.js') }}"></script> --}}
  
  {{-- <div id="myDiagramDiv" class="w-full h-screen" style="border: 1px solid black; position: relative; -webkit-tap-highlight-color: rgba(255, 255, 255, 0);">
    <canvas tabindex="0"  111 class="w-full h-screen" style="position: absolute; top: 0px; left: 0px; z-index: 2; user-select: none; touch-action: none;">This text is displayed if your browser does not support the Canvas HTML element.</canvas>
    <div style="position: absolute; overflow: auto; width: 398px; height: 398px; z-index: 1;">
      <div style="position: absolute; width: 1px; height: 1px;">JS Loading...</div>
    </div>
  </div>
  
  <textarea id="Message1"></textarea>
  <script>
    const nodeDataArray = {!! $nodeTreeArray !!}
  </script>
<script src="{{ asset('js/welcome-fortune.js') }}"></script> --}}

</div>
@endsection