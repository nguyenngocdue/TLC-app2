@if($content instanceof \Illuminate\Support\Collection)
    <div class='col-span-{{$colSpan}} grid gap-0'>
        <div class='grid grid-row-1'>
            <div class='grid grid-cols-12 text-right'>
                @if ($newLine)
                    @php
                        $value = $content->toArray();
                    @endphp
                    @if (!(sizeof($value) == 0 && ($control == 'attachment')) && !$hiddenLabel)
                        <label class='p-2 h-full w-full text-base font-medium flex col-span-{{$colSpan}} items-center justify-start col-start-1'>{{$label}}</label>
                    @endif
                    @if(sizeof($value) > 0)
                        @switch($control)
                            @case('attachment')
                                    <div class='p-2 border border-gray-600 text-sm font-normal col-span-{{$colSpan}} text-left'>
                                        <x-renderer.attachment2 name='attachment' :value="$value" destroyable={{false}} showToBeDeleted={{false}} showUploadFile={{false}} />
                                    </div>
                                @break
                            @case('checkbox')
                            @case('radio')
                            @case('dropdown')
                            @case('dropdown_multi')
                            <div class='p-2  border border-gray-600 text-sm font-normal col-span-{{$colSpan}} text-left'>
                                <x-print.checkbox-or-radio5 :relationships="$relationships" :value="$value" />
                            </div>
                                @break
                            @case('comment')
                                <div class='p-2  border border-gray-600 text-sm font-normal col-span-{{$colSpan}} text-left'>
                                    <x-print.comment5 :relationships="$relationships" :value="$value" />
                                </div>
                                @break
                            @case('relationship_renderer')
                                <div class='p1-2  border border-gray-600 text-sm font-normal col-span-{{$colSpan}} text-left'>
                                    <x-controls.relationship-renderer2 id={{$id}} type={{$type}} colName={{$columnName}} modelPath={{$modelPath}} noCss={{true}} />
                                </div>
                                @break
                            @default
                            <span class='p-2  border border-gray-600 text-sm font-normal col-span-{{$colSpan}} text-left'>{{$content}}</span>
                        @endswitch
                    @else
                        @if($control !== 'attachment')
                        <div class='h-[32px] p-2 border border-gray-600 text-sm font-normal col-span-{{$colSpan}} text-left'>
                            (None)
                        </div>    
                        @endif 

                    @endif
                @else
                    @php
                        $value = $content->toArray();
                    @endphp
                    @if(!(sizeof($value) == 0 && ($control == 'attachment')) && !$hiddenLabel)
                        <label class='p-2 border border-gray-600 bg-gray-50 h-full w-full text-base font-medium col-span-{{24/$colSpan}} items-center justify-end col-start-1'>{{$label}}</label>
                    @endif
                    @if(sizeof($value) > 0)
                        @switch($control)
                            @case('attachment')
                                <div class='p-2 border border-gray-600 text-sm font-normal col-start-{{24/$colSpan+1}} col-span-{{12 - 24/$colSpan}} text-left'>
                                    <x-renderer.attachment2 name='attachment' :value="$value" destroyable={{false}} showToBeDeleted={{false}} showUploadFile={{false}} />
                                </div>
                                @break
                            @case('checkbox')
                            @case('radio')
                            @case('dropdown')
                            @case('dropdown_multi')
                            <div class='p-2 border border-gray-600 text-sm font-normal col-start-{{24/$colSpan+1}} col-span-{{12 - 24/$colSpan}} text-left'>
                                <x-print.checkbox-or-radio5 :relationships="$relationships" :value="$value" />
                            </div>
                                @break
                            @case('comment')
                                <div class='p-2  border border-gray-600 text-sm font-normal col-start-{{24/$colSpan+1}} col-span-{{12 - 24/$colSpan}} text-left'>
                                    <x-print.comment5 :relationships="$relationships" :value="$value" />
                                </div>
                                @break
                            @case('relationship_renderer')
                                <div class='p-2  border border-gray-600 text-sm font-normal col-start-{{24/$colSpan+1}} col-span-{{12 - 24/$colSpan}} text-left'>
                                    <x-controls.relationship-renderer2 id={{$id}} type={{$type}} colName={{$columnName}} modelPath={{$modelPath}} />
                                </div>
                                @break
                            @default
                            <span class='p-2  border border-gray-600 text-sm font-normal col-start-{{24/$colSpan+1}} col-span-{{12 - 24/$colSpan}} text-left'>{{$content}}</span>
                        @endswitch
                    @else
                        @if($control !== 'attachment')
                        <div class='p-2  border border-gray-600 text-sm font-normal col-start-{{24/$colSpan+1}} col-span-{{12 - 24/$colSpan}} text-left'>
                        </div>   
                        @endif 
                    @endif
                @endif
            </div>
        </div>
    </div>
@elseif (is_string($content) | is_numeric($content))
<div class='col-span-{{$colSpan}} grid'>
    <div class='grid grid-rows-1'>
        <div class='grid grid-cols-12 text-right '>
            @if ($newLine)
                @if(!$hiddenLabel)
                    <label class='p-2 text-base font-medium h-full w-full flex col-span-{{$colSpan}} items-center justify-start col-start-1'>{{$label}}</label>
                @endif
                @if ($control == 'toggle')
                <span class='p-2 border border-gray-600 flex justify-start items-center text-sm font-normal col-span-{{$colSpan}} text-left'>{{$content == "1" ? "Yes" : "No"}}</span>
                <span class='p-2 border border-gray-600 flex justify-start items-center text-sm font-normal col-span-{{$colSpan}} text-left'>{{$content}}</span>
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
                    <label class='p-2 text-base font-medium h-full w-full flex col-span-{{$colSpan}} items-center justify-start col-start-1'>{{$label}}</label>
                @endif
                @if ($control == 'parent_link')
                <span class='p-2 border border-gray-600 flex justify-start items-center text-sm font-normal col-start-{{24/$colSpan+1}} col-span-{{12 - 24/$colSpan}} text-left'>
                    <x-print.parent-link5 :dataSource="$content"/>
                </span>
                @else
                <span class='p-2 border border-gray-600 flex justify-start items-center text-sm font-normal col-start-{{24/$colSpan+1}} col-span-{{12 - 24/$colSpan}} text-left'>
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
