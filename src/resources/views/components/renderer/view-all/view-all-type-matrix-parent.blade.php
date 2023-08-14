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
          {{-- topCenterControl="{!! $legends !!}" --}}
          topRightControl="{!! $perPage !!}"
          bottomRightControl="{!! $perPage !!}"
          
          rotate45Width="{{$rotate45Width}}"
          tableTrueWidth="{{$tableTrueWidth}}"
          headerTop="{{$headerTop}}"
          />

<x-renderer.legend type="{{$type}}" title="Legend of Status" />