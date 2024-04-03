@if($content instanceof \Illuminate\Support\Collection || is_array($content))
    <div class='col-span-{{$colSpan}} border border-gray-600'>
        <div class='grid grid-row-1'>
            <div class='grid grid-cols-12 text-right'>
                <x-print.render-description5
                newLine="{{$newLine}}" :item="$item"
                :content="$content" colSpan="{{$colSpan}}" hiddenLabel="{{$hiddenLabel}}"
                type="{{$type}}" columnName="{{$columnName}}" :relationships="$relationships" numberOfEmptyLines="{{$numberOfEmptyLines}}" modelPath="{{$modelPath}}"
                id="{{$id}}" control="{{$control}}" label="{{$label}}" printMode="{{$printMode}}"/>
            </div>
        </div>
    </div>
@elseif (is_string($content) | is_numeric($content))
    @if($control == 'textarea_diff_draft')
    @else
    @php
        $content = str_replace("\n", "<br/>", $content);
        $colSpan = $control == 'textarea_diff' ? 12 : $colSpan;
    @endphp
    <div class='col-span-{{$colSpan}} border border-gray-600'>
        <div class='grid grid-rows-1'>
            <div class='grid grid-cols-12 text-right '>
                @if ($newLine)
                    @if(!$hiddenLabel)
                        @if($control != 'textarea_diff_draft')
                        <label class='p-2 text-base font-medium h-full w-full flex col-span-12 items-center justify-start col-start-1'>{{$label}}</label>
                        @endif
                    @endif
                    @if ($control == 'toggle')
                    <span class='p-2 border border-gray-600 flex justify-start items-center text-sm font-normal col-span-12 text-left'>{{$content == "1" ? "Yes" : "No"}}</span>
                    @elseif ($control == 'textarea_diff')
                        <div class='p-2 border border-gray-600 flex justify-start items-center text-sm font-normal col-span-12 text-left'>
                            <x-controls.text-editor name="{{$columnName}}" :value="$content" />
                        </div>
                    @else
                    <span class='p-2 border border-gray-600 flex justify-start items-center text-sm font-normal col-span-12 text-left'>{!!$content!!}</span>
                    @endif
                @else
                    @if(!$hiddenLabel)
                        <label class='p-2 border-r border-gray-600 text-base font-medium bg-gray-50 h-full w-full flex col-span-{{24/$colSpan}} items-center justify-end col-start-1'>{{$label}}</label>
                    @endif
                    @if ($control == 'toggle')
                        <span class='p-2 bor1der bor1der-gray-600 flex justify-start items-center text-sm font-normal col-start-{{24/$colSpan+1}} col-span-{{12 - 24/$colSpan}} text-left'>{{$content == "1" ? "Yes" : "No"}}</span>
                    @else
                        <span class='p-2 bor1der bor1der-gray-600 flex justify-start items-center text-sm font-normal col-start-{{24/$colSpan+1}} col-span-{{12 - 24/$colSpan}} text-left'>{!!$content!!}</span>
                    @endif
                @endif
            </div>
        </div>
    </div>
    @endif
@else
<div class='col-span-{{$colSpan}} border border-gray-600'>
    <div class='grid grid-rows-1'>
        <div class='grid grid-cols-12 text-right '>
            @if ($newLine)
                @if(!$hiddenLabel)
                    <label class='p-2 text-base font-medium h-full w-full flex col-span-12 items-center justify-start col-start-1'>{{$label}}</label>
                @endif
                @if ($control == 'parent_link')
                <span class='p-2 bor1der bor1der-gray-600 flex justify-start items-center text-sm font-normal col-span-12 text-left'>
                    <x-print.parent-link5 :dataSource="$content"/>
                </span>
                @else
                <span class='p-2 bor1der bor1der-gray-600 flex justify-start items-center text-sm font-normal col-span-12 text-left'>
                    (None)
                </span>
                @endif
            @else
                @if(!$hiddenLabel)
                    <label class='p-2 border-r border-gray-600 text-base font-medium bg-gray-50 h-full w-full flex col-span-{{24/$colSpan}} items-center justify-end col-start-1'>{{$label}}</label>
                @endif
                @if ($control == 'parent_link')
                <span class='p-2 bord1er bord1er-gray-600 flex justify-start items-center text-sm font-normal col-start-{{24/$colSpan+1}} col-span-{{12 - 24/$colSpan}} text-left'>
                    <x-print.parent-link5 :dataSource="$content"/>
                </span>
                @else
                <span class='p-2 bord1er bor1der-gray-600 flex justify-start items-center text-sm font-normal col-start-{{24/$colSpan+1}} col-span-{{12 - 24/$colSpan}} text-left'>
                </span>
                @endif
            @endif
        </div>
    </div>
</div>
@endif
