<x-print.header5 :dataSource="$headerDataSource" type='{{$type}}'/>
<div class="flex flex-1 justify-center border-b">
    @php
    $nameRenderOfPageInfo = $headerDataSource->getQaqcInspTmpl->name ?? '';
    @endphp
    <x-renderer.heading level=5>{{strtoupper($nameRenderOfPageInfo)}}</x-renderer.heading>
</div>
<x-renderer.table borderColor="border-gray-600" maxH="{{false}}" :columns="$tableColumns" :dataSource="$tableDataSource" showNo="{{true}}" />