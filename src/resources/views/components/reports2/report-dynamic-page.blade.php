@php
    $CHART_TYPE_ID = 642;
    $PARAGRAPH_TYPE_ID = 643;
    $DESCRIPTION_TYPE_ID = 644;
@endphp


@foreach ($dataPerPage as $pageKey => $page)
    @foreach ($page as $rendererType => $blockItem) 
        @php
            $reportId = $report->id;
            $queriedData = $blockItem['queriedData'];
            $block = $blockItem['block'];
            $headerCols = $blockItem['headerCols'];
            $tableDataSource = $blockItem['tableDataSource'];
            $secondHeaderCols = $blockItem['secondHeaderCols'];
        @endphp
            @switch($rendererType)
                @case($CHART_TYPE_ID)
                    @php
                        $transformedFields = $blockItem['transformedFields'];
                    @endphp
                    <x-reports2.report-block-chart 
                        :block="$block" 
                        reportId="{{$reportId}}" 
                        :queriedData="$queriedData"
                        :transformedFields="$transformedFields"
                        :headerCols="$headerCols"
                        :currentParams="$currentParams"
                        />
                    @break

                @case($PARAGRAPH_TYPE_ID)
                    <x-reports2.report-block-paragraph 
                        :queriedData="$queriedData" 
                        :block="$block" 
                        reportId="{{$reportId}}" 
                        :currentParams="$currentParams"
                        :currentFormattedParams="$currentFormattedParams"
                        />
                    @break

                @case($DESCRIPTION_TYPE_ID)
                    <x-reports2.report-block-description :block="$block" reportId="{{ $reportId }}" />
                    @break

                @default
                   
                    @if (!$rendererType)
                        @dump($sqlString)
                        @dump($queriedData)
                    @endif
                    <x-reports2.report-block-table 
                        reportId="{{ $reportId }}" 
                        :block="$block"
                        :tableDataSource="$tableDataSource" 
                        :headerCols="$headerCols"
                        :secondHeaderCols="$secondHeaderCols"
                        :currentParams="$currentParams"
                        :currentFormattedParams="$currentFormattedParams"
                        :queriedData="$queriedData"
                        />
                    @if(!$block->renderer_type)
                        <x-renderer.button href="{{ route('rp_blocks.edit', $block->id) }}" type="warning" title="{{ $block->name }}">
                            Kindly select a type of renderer for block.
                        </x-renderer.button>
                    @endif
            @endswitch
    @endforeach
@endforeach