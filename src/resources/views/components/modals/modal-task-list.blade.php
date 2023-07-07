@extends("modals.modal-large")

@section($modalId.'-header', "Projects and Task list of ".$userCurrentCalendar->name." (".$userCurrentCalendar->getUserDiscipline->name.")")

@section($modalId.'-body')

<div class="grid grid-cols-12 h-full">
  <div class="col-span-12 lg:col-span-6">
    <div id="myDiagramProjectDiv" class="w-full h-full border" style="position: relative; -webkit-tap-highlight-color: rgba(255, 255, 255, 0);">
      <canvas tabindex="0" class="w-full" style="position: absolute; top: 0px; left: 0px; z-index: 2; user-select: none; touch-action: none;">This text is displayed if your browser does not support the Canvas HTML element.</canvas>
      <div style="position: absolute; overflow: auto; width: 398px; height: 398px; z-index: 1;">
        <div style="position: absolute; width: 1px; height: 1px;">JS Loading...</div>
      </div>
    </div>
  </div>
  <div class="col-span-12 lg:col-span-6">
    <div id="myDiagramTaskListDiv" class="w-full h-full border" style="position: relative; -webkit-tap-highlight-color: rgba(255, 255, 255, 0);">
      <canvas tabindex="0" class="w-full" style="position: absolute; top: 0px; left: 0px; z-index: 2; user-select: none; touch-action: none;">This text is displayed if your browser does not support the Canvas HTML element.</canvas>
      <div style="position: absolute; overflow: auto; width: 398px; height: 398px; z-index: 1;">
        <div style="position: absolute; width: 1px; height: 1px;">JS Loading...</div>
      </div>
    </div>
  </div>
</div> 

@endsection

{{-- @section($modalId.'-footer')
@endsection --}}

@section($modalId.'-javascript')
<script>
  const projectDataArray = {!! $nodeProjectTreeArray !!}
    const taskListDataArray = {!! $nodeTaskTreeArray !!}
</script>
<script src="{{ asset('js/modals/modal-task-list.blade.js') }}"></script>
@endsection