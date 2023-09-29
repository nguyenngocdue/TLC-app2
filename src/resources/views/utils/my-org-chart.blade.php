@extends('layouts.app')
@section('topTitle', $topTitle)
@section('title', '')

@section('content')
<div >
    @if($isAdmin)
    <div class='p-2'>
        <x-renderer.button href="/my-org-chart" type="{{$settingsView ? '' : 'secondary'}}">Position ORG Chart </x-renderer.button>
        <x-renderer.button href="/my-org-chart?approval-tree=true" type="{{$settingsView ? 'secondary' : ''}}">Approval Tree</x-renderer.button>
    </div>
    @endif
    <div class="no-print">
        <form class="" method="POST" action="{{route('updateUserSettings')}}" >
            @method('PUT')
            @csrf
            <input type="hidden" name='action' value="updateShowOptionsOrgChart">
            <x-renderer.card title="">
            <div class="justify-between grid grid-cols-12 gap-5 items-center">
                {{-- <div class="col-span-4">
                <label for="">Workplace</label>
                    <x-utils.filter-workplace tableName="workplaces" multiple="true" name="show_options[workplace][]" id="workplace" typeToLoadListener="" :selected="$showOptions['workplace'] ?? []"></x-utils.filter-workplace>
                </div> --}}
                <div class="col-span-4">
                <label for="">Head of Department</label>
                <x-utils.filter-head-of-department tableName="departments" name="show_options[department]" id="department" typeToLoadListener="" selected="{{$showOptions['department'] ?? ''}}"></x-utils.filter-head-of-department>
                </div>
                <div class="col-span-1"></div>
                <div class="col-span-4">
                <label for="">Show Options</label>
                <x-renderer.card title="" class="bg-white border p-2">
                    <div class="justify-evenly flex">
                        <label>BOD
                            <x-renderer.editable.checkbox name="show_options[is_bod]" cell="{{$showOptions['is_bod'] ?? ''}}"></x-renderer.editable.checkbox>
                        </label>
                        <label class="ml-3">Workers
                        <x-renderer.editable.checkbox name="show_options[time_keeping_type]" cell="{{$showOptions['time_keeping_type'] ?? ''}}"></x-renderer.editable.checkbox>
                        </label>
                        <label class="ml-3">Resigned
                        <x-renderer.editable.checkbox name="show_options[resigned]" cell="{{$showOptions['resigned'] ?? ''}}"></x-renderer.editable.checkbox>
                        </label>
                    </div>
                </x-renderer.card>
                </div>
                <div class="col-span-3 flex justify-end mt-5">
                    <x-renderer.button htmlType='submit' type="secondary" class="w-20" >Save</x-renderer.button>
                </div>
            </div>
            </x-renderer.card>
        </form>
    </div>
    <script src="{{ asset('js/go_debug@2.3.8.js') }}"></script>
    <div class="flex items-center justify-center">
        <x-controls.text2 type="search" class="w-[550px] mr-1 my-2" name="mySearch"
        placeholder="Press ENTER to search, and Press SPACE to pan to the next result"
        value="" onkeypress="if (event.keyCode === 13) searchDiagram()" />
        <x-renderer.button type="secondary" onClick="searchDiagram()" class="w-20" >Search</x-renderer.button>
    </div>
    <div class="relative">
        <div id="myOverviewDiv" class="w-60 h-60 absolute top-1 left-1 border bg-gray-100 border-gray-100"
        style="cursor: move; z-index:19;">
            <canvas tabindex="0" width="198" height="98" style="position: absolute; top: 0px; left: 0px; z-index: 2; user-select: none; touch-action: none; width: 198px; height: 98px; cursor: move;"></canvas>
            <div style="position: absolute; overflow: auto; width: 198px; height: 98px; z-index: 1;">
                <div style="position: absolute; width: 1px; height: 1px;"></div>
            </div>
        </div> <!-- Styled in a <style> tag at the top of the html page -->
        <div id="myDiagramDiv" class="w-full h-screen bg-white"
        style="border: 1px solid black; position: relative; -webkit-tap-highlight-color: rgba(255, 255, 255, 0);">
            <canvas tabindex="0"  111 class="w-full h-screen" style="position: absolute; top: 0px; left: 0px; z-index: 2; user-select: none; touch-action: none;">This text is displayed if your browser does not support the Canvas HTML element.</canvas>
            {{-- <div style="position: absolute; overflow: auto; width: 398px; height: 398px; z-index: 1;">
            <div style="position: absolute; width: 1px; height: 1px;">JS ERROR</div>
            </div> --}}
        </div>

    </div>
    <script>
        nodeDataArray = @json($dataSource);
    </script>
    <script src="{{ asset('js/my-org-chart.js') }}">
    </script>
</div>
@endsection
