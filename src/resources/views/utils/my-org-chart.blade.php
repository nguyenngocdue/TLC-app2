@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')
<div>
  
  <form class="mb-5" method="POST" action="{{route('updateUserSettings')}}">
    @method('PUT')
    @csrf
    <input type="hidden" name='action' value="updateShowOptionsOrgChart">
    <x-renderer.card title="Shows">
      <div class="justify-between grid grid-cols-12 gap-5 items-center">
        <div class="col-span-4">
          <label for="">Workplace</label>
              @php
                $selectedWorkplace = join(',',$showOptions['workplace']) ?? '';
              @endphp
              <x-utils.filter-workplace tableName="workplaces" multiple="true" name="show_options[workplace][]" id="workplace" typeToLoadListener="" selected="{{$selectedWorkplace}}"></x-utils.filter-workplace>
        </div>
        <div class="col-span-4">
          <label for="">Head of Department</label>
          <x-utils.filter-head-of-department tableName="departments" name="show_options[department]" id="department" typeToLoadListener="" selected="{{$showOptions['department'] ?? ''}}"></x-utils.filter-head-of-department>
        </div>
        <div class="col-span-3">
          <label for="">Options</label>
          <div>
            <label for="">Resigned</label>
            <x-renderer.editable.checkbox name="show_options[resigned]" cell="{{$showOptions['resigned'] ?? ''}}"></x-renderer.editable.checkbox>
            <label for="" class="ml-3">Worker</label>
            <x-renderer.editable.checkbox name="show_options[time_keeping_type]" cell="{{$showOptions['time_keeping_type'] ?? ''}}"></x-renderer.editable.checkbox>
          </div>
        </div>
        <x-renderer.button htmlType='submit'>Save</x-renderer.button>
      </div>
    </x-renderer.card>
  </form>
  <script src="{{ asset('js/go_debug@2.3.8.js') }}"></script>
  <div id="myDiagramDiv" class="w-full h-screen" style="border: 1px solid black; position: relative; -webkit-tap-highlight-color: rgba(255, 255, 255, 0);">
    <canvas tabindex="0"  111 class="w-full h-screen" style="position: absolute; top: 0px; left: 0px; z-index: 2; user-select: none; touch-action: none;">This text is displayed if your browser does not support the Canvas HTML element.</canvas>
    {{-- <div style="position: absolute; overflow: auto; width: 398px; height: 398px; z-index: 1;">
      <div style="position: absolute; width: 1px; height: 1px;">JS ERROR</div>
    </div> --}}
  </div>
  
  <script>
    nodeDataArray = @json($dataSource);
  </script>
<script src="{{ asset('js/my-org-chart.js') }}">
</script>
</body>
</div>

@endsection