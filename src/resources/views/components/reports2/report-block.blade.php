@php
    $TABLE_TYPE_ID = 641;
    $CHART_TYPE_ID = 642;
    $PARAGRAPH_TYPE_ID = 643;
    $DESCRIPTION_TYPE_ID = 644;
@endphp
@if ($blockDataSource)
    @foreach ($blockDataSource as $key => $blockItem)
        @php
            $block = $blockItem['block'];
            $tableDataSource = $blockItem['tableDataSource'];
            $headerCols = $blockItem['headerCols'];

            $rendererType = $block->renderer_type;
            $colSpan = $blockItem['colSpan'] ?? 12;

            $queriedData = $blockItem['queriedData']; 

            $secondHeaderCols = $blockItem['secondHeaderCols'];

            $background = $blockItem['backgroundBlock'];
            $backgroundPath = isset($background->url_media)
                ? app()->pathMinio() . $background->url_media 
                : null;
        @endphp
        <div title="{{ $block->name }}"
            class="col-span-{{ $colSpan }} {{ $backgroundPath ? '' : '' }} p-4 text-center bg-cover bg-center "
            @if ($backgroundPath) style="background-image: url('{{ $backgroundPath }}');" @endif>

            <x-renderer.report2.title-description-block :block="$block" />
            @switch($rendererType)
                @case($TABLE_TYPE_ID)
                    <x-reports2.report-block-table 
                        reportId="{{ $reportId }}" 
                        :block="$block"
                        :tableDataSource="$tableDataSource" 
                        :headerCols="$headerCols"
                        :secondHeaderCols="$secondHeaderCols"
                        :currentParams="$currentParams"
                        
                        />
                @break

                @case($CHART_TYPE_ID)
                    <x-reports2.report-block-chart :block="$block" reportId="{{ $reportId }}" :queriedData="$queriedData"
                        :headerCols="$headerCols" />
                @break

                @case($PARAGRAPH_TYPE_ID)
                    <x-reports2.report-block-paragraph :block="$block" reportId="{{ $reportId }}" />
                @break

                @case($DESCRIPTION_TYPE_ID)
                    <x-reports2.report-block-description :block="$block" reportId="{{ $reportId }}" />
                @break

                @default
                    @dump($block->sql_string)
                    @dump($queriedData)
                    <x-renderer.button href="{{ route('rp_blocks.edit', $block->id) }}" type="warning" title="{{ $block->name }}">
                        Kindly select a type of renderer for block.
                    </x-renderer.button>
                @break
            @endswitch

        </div>
    @endforeach
@endif
