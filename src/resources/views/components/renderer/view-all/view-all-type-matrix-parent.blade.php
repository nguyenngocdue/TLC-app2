{!! $filterRenderer !!}

<x-renderer.table 
          showPaginationTop="true"
          :columns="$columns"
          :dataSource="$dataSource"
          :dataHeader="$dataHeader"
          groupBy="{{$groupBy}}"
          groupByLength="{{$groupByLength}}"
          bottomLeftControl="{!! $footer !!}"
          showNo=1
          topLeftControl="{!!$actionButtons!!}"
          topRightControl="{!! $perPage !!}"
          bottomRightControl="{!! $perPage !!}"
          rotate45Width="{{$rotate45Width}}"
          tableTrueWidth="{{$tableTrueWidth}}"
          headerTop="{{$headerTop}}"
          />