<x-renderer.tab-pane :tabs="$tabPane">
    <div class="p-2 bg-white">
        <x-renderer.table 
            showNo="true" 
            :columns="$columns" 
            :dataSource="$dataSource" 
            showPaginationTop="true"
            topLeftControl="{!! $actionButtonGroup !!}"
            topRightControl="{!! $perPage !!}"
            bottomLeftControl="{!! $actionMultipleGroup !!}"
            bottomRightControl="{!! $perPage !!}"
            tableTrueWidth={{$tableTrueWidth}}
            />
    </div>
</x-renderer.tab-pane>