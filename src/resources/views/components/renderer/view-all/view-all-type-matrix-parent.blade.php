<x-renderer.view-all.view-all-type-matrix-filter :type="$type" :dataSource="$filterDataSource" :viewportParams="$viewportParams"/>
<x-renderer.table 
          :columns="$columns"
          :dataSource="$dataSource"
          groupBy="name_for_group_by"
          groupByLength=2
          footer="{!! $footer !!}"
          showNo=1
          {{-- tableTrueWidth="{{true}}" --}}
          />