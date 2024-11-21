<x-renderer.tab-pane :tabs="$tabPane">
    <div class="p-2 bg-white">
        <x-renderer.table 
            showNo="true" 
            :columns="$columns" 
            :dataSource="$dataSource" 
            showPaginationTop="true"
            topLeftControl="{!! $actionButtonGroup !!}"
            bottomLeftControl="{!! $actionMultipleGroup !!}"
            topRightControl="{!! $perPage !!}"
            bottomRightControl="{!! $perPage !!}"
            tableTrueWidth={{$tableTrueWidth}}
            maxH="{{656}}"
            />
    </div>
</x-renderer.tab-pane>