@if($blocks)
    @foreach ($blocks as $key => $values)
    @php
        $block = $values['blocks'];
        //dd($block);
        $rendererType = $block->renderer_type;
        $colSpan = $values['col_span'] ?? 12;
        $background = $values['background_block'];
        $backgroundPath = isset($background->url_media)? "'".env('AWS_ENDPOINT').'/tlc-app//'.$background->url_media."'" : '';
    @endphp
        <div title="{{ $block ->name}}" 
             class="col-span-{{$colSpan}} {{$backgroundPath ? '' : 'bg-gray-300'}} p-4 text-center bg-cover bg-center "
            @if($backgroundPath) style="background-image: url({{$backgroundPath}});" @endif> 

            {{-- ID: {{ $block -> id}} <br/> --}}
            {{-- Name: {{ $block -> name}} --}}
            @switch($rendererType)
                @case(641)
                    <x-reports2.table-block-report :block="$block"/>
                    @break
                @case(642)
                    <x-reports2.chart-block-report :block="$block"/>
                    @break
                @case(643)
                    <x-reports2.paragraph-block-report :block="$block"/>
                    @break
                @default
                    <span>Select a type of renderer.</span>
                @break 
            @endswitch

        </div>
    @endforeach    
@endif
