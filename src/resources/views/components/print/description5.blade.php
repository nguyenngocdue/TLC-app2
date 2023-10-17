@if($content instanceof \Illuminate\Support\Collection)
    <div class='col-span-{{$colSpan}} grid gap-0'>
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
<div class='col-span-{{$colSpan}} grid'>
    <div class='grid grid-rows-1'>
        <div class='grid grid-cols-12 text-right '>
            @if ($newLine)
            
                @if(!$hiddenLabel)
                    <label class='p-2 text-base font-medium h-full w-full flex col-span-12 items-center justify-start col-start-1'>{{$label}}</label>
                @endif
                @if ($control == 'toggle')
                <span class='p-2 border border-gray-600 flex justify-start items-center text-sm font-normal col-span-12 text-left'>{{$content == "1" ? "Yes" : "No"}}</span>
                @else
                <span class='p-2 border border-gray-600 flex justify-start items-center text-sm font-normal col-span-12 text-left'>{{$content}}</span>
                @endif
            @else
                @if(!$hiddenLabel)
                    <label class='p-2 border border-gray-600 text-base font-medium bg-gray-50 h-full w-full flex col-span-{{24/$colSpan}} items-center justify-end col-start-1'>{{$label}}</label>
                @endif
                @if ($control == 'toggle')
                    <span class='p-2 border border-gray-600 flex justify-start items-center text-sm font-normal col-start-{{24/$colSpan+1}} col-span-{{12 - 24/$colSpan}} text-left'>{{$content == "1" ? "Yes" : "No"}}</span>
                @else
                    <span class='p-2 border border-gray-600 flex justify-start items-center text-sm font-normal col-start-{{24/$colSpan+1}} col-span-{{12 - 24/$colSpan}} text-left'>{{$content}}</span>
                @endif
            @endif

        </div>
    </div>
</div>

@else
<div class='col-span-{{$colSpan}} grid'>
    <div class='grid grid-rows-1'>
        <div class='grid grid-cols-12 text-right '>
            @if ($newLine)
                @if(!$hiddenLabel)
                    <label class='p-2 text-base font-medium h-full w-full flex col-span-12 items-center justify-start col-start-1'>{{$label}}</label>
                @endif
                @if ($control == 'parent_link')
                <span class='p-2 border border-gray-600 flex justify-start items-center text-sm font-normal col-span-12 text-left'>
                    <x-print.parent-link5 :dataSource="$content"/>
                </span>
                @else
                <span class='p-2 border border-gray-600 flex justify-start items-center text-sm font-normal col-span-12 text-left'>
                    (None)
                </span>
                @endif
            @else
                @if(!$hiddenLabel)
                    <label class='p-2 border border-gray-600 text-base font-medium bg-gray-50 h-full w-full flex col-span-{{24/$colSpan}} items-center justify-end col-start-1'>{{$label}}</label>
                @endif
                @if ($control == 'parent_link')
                <span class='p-2 border border-gray-600 flex justify-start items-center text-sm font-normal col-start-{{24/$colSpan+1}} col-span-{{12 - 24/$colSpan}} text-left'>
                    <x-print.parent-link5 :dataSource="$content"/>
                </span>
                @else
                <span class='p-2 border border-gray-600 flex justify-start items-center text-sm font-normal col-start-{{24/$colSpan+1}} col-span-{{12 - 24/$colSpan}} text-left'>
                </span>
                @endif
            @endif
        </div>
    </div>
</div>
@endif
