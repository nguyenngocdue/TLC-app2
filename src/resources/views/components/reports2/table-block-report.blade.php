<x-renderer.heading level=5 xalign='left'>{{$name}}</x-renderer.heading>
<x-renderer.heading level=6 xalign='left'>{{$description}}</x-renderer.heading>

{{-- @dump($rotate45Width, $rotate45Height) --}}
<x-renderer.card title="">
    {{-- showNo= { { true } } --}}
    <x-renderer.table 
        headerTop=12 
        showNo=true
        :columns="$tableColumns" 
        :dataSource="$tableDataSource"
        tableTrueWidth="{{$tableTrueWidth}}"
        maxH="{{$maxHeight}}"
        rotate45width="{{$rotate45Width}}"
        rotate45width="{{true}}"
        rotate45Height="{{$rotate45Height}}"

        showPaginationTop=true
        page-limit=10
        />
</x-renderer.card>