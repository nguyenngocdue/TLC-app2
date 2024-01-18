
<div style="{{$layout}}" class="items-center bg-white box-border px-8">
    <x-print.header5 :dataSource="$headerDataSource" type="{{$type}}"/>
    <div class="flex flex-1 justify-center mt-3">
        <x-renderer.heading level=5>{{strtoupper($headerDataSource->name).' '. (isset($page) ? '('. $page .')' : '')}}</x-renderer.heading>
    </div>
    <x-renderer.table maxH="{{false}}" :columns="$tableColumns" :dataSource="$tableDataSource" groupKeepOrder="{{true}}" groupBy="group_description" groupByLength=100 showNo="{{true}}" />
</div> 