<x-renderer.view-all.view-all-type-matrix-filter :type="$type" :dataSource="$filterDataSource" :viewportParams="$viewportParams"/>
<x-renderer.table 
          showPaginationTop="true"
          :columns="$columns"
          :dataSource="$dataSource"
          groupBy="{{$groupBy}}"
          groupByLength="{{$groupByLength}}"
          bottomLeftControl="{!! $footer !!}"
          showNo=1
          topRightControl="{!! $perPage !!}"
          bottomRightControl="{!! $perPage !!}"
          rotate45Width="{{$rotate45Width}}"
          />