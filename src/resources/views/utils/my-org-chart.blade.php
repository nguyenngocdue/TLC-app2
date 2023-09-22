@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')
<div >
    <div>
        <x-renderer.button href="/my-org-chart" type="{{$settingsView ? '' : 'secondary'}}">Position Org Chart </x-renderer.button>
        <x-renderer.button href="/my-org-chart?approval-tree=true" type="{{$settingsView ? 'secondary' : ''}}">Approval Tree Org Chart </x-renderer.button>
    </div>
    <div class="no-print">
        <form class="mb-5" method="POST" action="{{route('updateUserSettings')}}" >
            @method('PUT')
            @csrf
            <input type="hidden" name='action' value="updateShowOptionsOrgChart">
            <x-renderer.card title="Shows">
            <div class="justify-between grid grid-cols-12 gap-5 items-center">
                <div class="col-span-4">
                <label for="">Workplace</label>
                    <x-utils.filter-workplace tableName="workplaces" multiple="true" name="show_options[workplace][]" id="workplace" typeToLoadListener="" :selected="$showOptions['workplace'] ?? []"></x-utils.filter-workplace>
                </div>
                <div class="col-span-4">
                <label for="">Head of Department</label>
                <x-utils.filter-head-of-department tableName="departments" name="show_options[department]" id="department" typeToLoadListener="" selected="{{$showOptions['department'] ?? ''}}"></x-utils.filter-head-of-department>
                </div>
                <div class="col-span-3">
                <label for="">Show Options</label>
                <div>
                    <label class="ml-3">BOD
                        <x-renderer.editable.checkbox name="show_options[is_bod]" cell="{{$showOptions['is_bod'] ?? ''}}"></x-renderer.editable.checkbox>
                    </label>
                    <label>Resigned
                    <x-renderer.editable.checkbox name="show_options[resigned]" cell="{{$showOptions['resigned'] ?? ''}}"></x-renderer.editable.checkbox>
                    </label>
                    <label class="ml-3">Workers
                    <x-renderer.editable.checkbox name="show_options[time_keeping_type]" cell="{{$showOptions['time_keeping_type'] ?? ''}}"></x-renderer.editable.checkbox>
                    </label>
                </div>
                </div>
                <x-renderer.button htmlType='submit' type="secondary" >Save</x-renderer.button>
            </div>
            </x-renderer.card>
        </form>
    </div>
    <script src="{{ asset('js/go_debug@2.3.8.js') }}"></script>
    <input type="search" id="mySearch" onkeypress="if (event.keyCode === 13) searchDiagram()">
    <button onclick="searchDiagram()">Search</button>
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
</div>
@endsection
