{!! $filterRenderer !!}
<form action="{{$route}}" method="POST" id="form-upload" enctype="multipart/form-data">
    @csrf
    @method('PUT')
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
            topCenterControl="<div class='flex items-center text-lg'>{!! $tableTopCenterControl !!}</div>"
            topRightControl="{!! $perPage !!}"
            bottomRightControl="{!! $perPage !!}"
            
            rotate45Width="{{$rotate45Width}}"
            rotate45Height="{{$rotate45Height}}"
            tableTrueWidth="{{$tableTrueWidth}}"
            headerTop="{{$headerTop}}"
            />
</form>
<x-renderer.legend type="{{$type}}" title="Legend of Status" />