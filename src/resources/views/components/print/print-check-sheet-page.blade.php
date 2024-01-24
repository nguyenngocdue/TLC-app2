
<div style="{{$layout}}" class="items-center bg-white box-border px-8">
    <x-print.header5 :dataSource="$headerDataSource" type="{{$type}}"/>
    <div component="print-check-sheet-page-header" id="{{Str::slug($headerDataSource->name)}}" class="flex flex-1 justify-center mt-3">        
        <x-renderer.heading level=5>{{strtoupper($headerDataSource->name).' '. (isset($page) ? '('. $page .')' : '')}}</x-renderer.heading>
    </div>
    <x-renderer.progress-bar :dataSource="$progressData" height="40px" />
    <x-renderer.table maxH="{{false}}" :columns="$tableColumns" :dataSource="$tableDataSource" groupKeepOrder="{{true}}" groupBy="group_description" groupByLength=100 showNo="{{true}}" />
</div> 