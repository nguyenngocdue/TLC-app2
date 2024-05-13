{!! $filterRenderer !!}

{{-- @dump($matrixes) --}}

@foreach($matrixes as $matrixValue)
@php
    $name = $matrixValue['name'];
    $description = $matrixValue['description'];
    $columns = $matrixValue['columns'];
    $dataSource = $matrixValue['dataSource'];
    $dataHeader = $matrixValue['dataHeader'];
@endphp

@if(sizeof($matrixes) > 1)
    <div class="flex">
        <div class="w-1/2 font-semibold">{{$name}}</div>
        <div class="w-1/2 text-right italic">{{$description}}</div>
    </div>
@endif

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
            topCenterControl="<div class='flex items-center justify-center font-bold gap-3'>{!! $tableTopCenterControl !!}</div>"
            topRightControl="{!! $perPage !!}"
            bottomRightControl="{!! $perPage !!}"
            
            rotate45Width="{{$rotate45Width}}"
            rotate45Height="{{$rotate45Height}}"
            tableTrueWidth="{{$tableTrueWidth}}"
            headerTop="{{$headerTop}}"
            maxH="{{$maxH}}"
            />
</form>

@endforeach

@if($showLegend)
    <x-renderer.legend type="{{$type}}" title="Legend of Status" />
@endif