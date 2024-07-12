@if($blocks)
    {{-- @dd($blocks) --}}
    @foreach ($blocks as $key => $values)
    @php
        $block = $values['blocks'];
        $colSpan = $values['col_span'];
        $background = $values['background_block'];
        $backgroundPath = isset($background->url_media)? "'".env('AWS_ENDPOINT').'/tlc-app//'.$background->url_media."'" : '';
    @endphp
        <div title="{{ $block ->name}}" 
             class="col-span-{{ $colSpan}} {{$backgroundPath ? '' : 'bg-orange-300'}} p-4 text-center bg-cover bg-center "
            @if($backgroundPath) style="background-image: url({{$backgroundPath}});" @endif> 
            ID: {{ $block -> id}} <br/>
            Name: {{ $block -> name}}
        </div>
    @endforeach    
@endif
