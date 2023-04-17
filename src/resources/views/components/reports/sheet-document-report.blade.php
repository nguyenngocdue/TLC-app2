@foreach($dataSource as $idSheet => $data)
<div class="w-[1000px] min-h-[1415px] items-center bor1der bg-white box-border p-8">
    <x-reports.header-sheet-report :dataSource="array_pop($data)" />
    <x-renderer.table maxH="{{false}}" :columns="$columns" :dataSource="$dataSource[$idSheet]" groupKeepOrder="{{true}}" groupBy="group_description" groupByLength=100 showNo="{{true}}" />
</div>
<x-renderer.page-break />
@endforeach
