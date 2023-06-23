{{-- @extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

<div>

</div>
@endsection --}}

<script src="{{ asset('js/go@2.2.23.js') }}"></script>

 <div id="sample">
  <div id="diagramDiv" style="border: 1px solid black; width: 400px; height: 400px; position: relative; -webkit-tap-highlight-color: rgba(255, 255, 255, 0);">
    <canvas tabindex="0" width="398" height="398" style="position: absolute; top: 0px; left: 0px; z-index: 2; user-select: none; touch-action: none; width: 398px; height: 398px;">This text is displayed if your browser does not support the Canvas HTML element.</canvas>
    <div style="position: absolute; overflow: auto; width: 398px; height: 398px; z-index: 1;">
      <div style="position: absolute; width: 1px; height: 1px;">JS ERROR</div>
    </div>
  </div>
</div>
<textarea id="Message1"></textarea>
<script src="{{ asset('js/welcome-fortune.js') }}"></script>

</body>
</html>
