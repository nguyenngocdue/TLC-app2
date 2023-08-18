{!! $filterRenderer !!}
@if($showPrintButton)
<form action="{{route($type.'_prt.print')}}" method="POST" id="form-upload" enctype="multipart/form-data">
    @csrf
    @method('PUT')
@endif
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
@if($showPrintButton)
    </form>
@endif
    </form>


<x-renderer.legend type="{{$type}}" title="Legend of Status" />