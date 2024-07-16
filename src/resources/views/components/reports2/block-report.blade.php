@if($blocksDataSource)
    @foreach ($blocksDataSource as $key => $values)
    @php
        $block = $values['blocks'];
        $tableDataSource = $values['tableDataSource'];
        $tableColumns = $values['tableColumns'];

        $rendererType = $block->renderer_type;
        $colSpan = $values['colSpan'] ?? 12;

        $background = $values['backgroundBlock'];
        $backgroundPath = isset($background->url_media)? "'".env('AWS_ENDPOINT').'/tlc-app//'.$background->url_media."'" : '';
    @endphp
        <div title="{{ $block ->name}}" 
             class="col-span-{{$colSpan}} {{$backgroundPath ? '' : 'bg-gray-200'}} p-4 text-center bg-cover bg-center "
            @if($backgroundPath) style="background-image: url({{$backgroundPath}});" @endif> 

            @switch($rendererType)
                @case(641)
                    <x-reports2.table-block-report 
                            reportId="{{$reportId}}"
                            :blockDataSource="$values"
                            :tableDataSource="$tableDataSource"
                            :tableColumns="$tableColumns"
                            :block="$block"
                        />
                    @break
                @case(642)
                    <x-reports2.chart-block-report 
                            :block="$block"
                            reportId="{{$reportId}}"
                        />
                    @break
                @case(643)
                    <x-reports2.paragraph-block-report 
                            :block="$block"
                            reportId="{{$reportId}}"
                        />
                    @break
                @case(644)
                    <x-reports2.description-block-report 
                            :block="$block"
                            reportId="{{$reportId}}"
                        />
                    @break
                @default
                    <span class="text-lg text-green-600 font-semibold">
                        Kindly select a type of renderer.
                    </span>

                @break 
            @endswitch

        </div>
    @endforeach    
@endif
