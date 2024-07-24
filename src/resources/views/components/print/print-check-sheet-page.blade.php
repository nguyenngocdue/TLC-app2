<div style1="{{$layout}}" class="p-4vw w-90vw mx-auto bg-white">
    <x-print.header5 :dataSource="$headerDataSource" type="{{$type}}"/>
    <div component="print-check-sheet-page-header" id="{{Str::slug($headerDataSource->name)}}" class="flex flex-1 justify-center mt-3">        
        {{-- <x-renderer.heading level=4>{{strtoupper($headerDataSource->name).' '. (isset($page) ? '('. $page .')' : '')}}</x-renderer.heading> --}}
        <div class="text-xl-vw font-bold p-1vw">{{strtoupper($headerDataSource->name).' '. (isset($page) ? '('. $page .')' : '')}}</div>
    </div>
    <x-renderer.progress-bar :dataSource="$progressData" height="40px" />
    <x-renderer.table borderColor="border-gray-600" maxH="{{false}}" :columns="$tableColumns" :dataSource="$tableDataSource" groupKeepOrder="{{true}}" groupBy="group_description" groupByLength=100 showNo="{{true}}" />
</div> 