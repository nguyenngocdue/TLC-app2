@if($blocks)
    @foreach ($blocks as $key => $value)
        <div title="{{$value['order_no']}}" class="col-span-{{$value['col_span']}} bg-gray-200 p-4 text-center">
        Col Span = {{$value['col_span']}}
        {{$value['name']}}
        {{$value['renderer_type']?? null}}
        </div>
    @endforeach    
@endif
