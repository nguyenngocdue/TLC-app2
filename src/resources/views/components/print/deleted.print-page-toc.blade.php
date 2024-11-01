<x-print.header5 :dataSource="$headerDataSource" type='{{$type}}'/>
<div class="flex flex-1 justify-center border1-b">
    <x-renderer.heading level=5 class="font-bold p-1vw uppercase">{{$headerDataSource->getQaqcInspTmpl->name ?? ''}}</x-renderer.heading>
</div>
<x-renderer.table borderColor="border-gray-600" maxH="{{false}}" :columns="$tableColumns" :dataSource="$tableDataSource" showNo="{{true}}" />