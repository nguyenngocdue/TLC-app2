@if($blocks)
    @foreach ($blocks as $key => $value)
    @php
        $backgroundPath = isset($value['url_media']) && $value['url_media'] ? "'".env('AWS_ENDPOINT').'/tlc-app//'.$value['url_media']."'" : '';
    @endphp
        <div title="{{ $value['order_no'] }}" 
             class="col-span-{{ $value['col_span'] }} {{ !$backgroundPath ? 'bg-slate-500' : ''}} p-4 text-center bg-cover bg-center"
            @if($backgroundPath) style="background-image: url({{$backgroundPath}});" @endif> 
            Col Span = {{ $value['col_span'] }}
            <br/>
        </div>
    @endforeach    
@endif
