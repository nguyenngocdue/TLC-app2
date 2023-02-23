@if($content instanceof \Illuminate\Database\Eloquent\Collection)
    <div class='col-span-{{$colSpan}} grid gap-0'>
        <div class='grid grid-row-1'>
            <div class='grid grid-cols-12 text-right'>
                <label class='p-2 border border-gray-600 bg-gray-50 h-full w-full flex text-base font-medium col-span-{{24/$colSpan}} items-center justify-end col-start-1'>{{$label}}</label>
                @php
                    // $value = $content->sortByDesc('created_at')->toArray();
                    $value = $content->toArray();
                @endphp
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
                    <div class='p-2  border border-gray-600 text-sm font-normal col-start-{{24/$colSpan+1}} col-span-{{12 - 24/$colSpan}} text-left'>
                        <x-renderer.checkbox-or-radio :relationships="$relationships" :value="$value" />
                    </div>
                        @break
                    @case('comment')
                        <div class='p-2  border border-gray-600 text-sm font-normal col-start-{{24/$colSpan+1}} col-span-{{12 - 24/$colSpan}} text-left'>
                            <x-renderer.comment3 :relationships="$relationships" :value="$value" />
                        </div>
                        @break
                    @case('relationship_renderer')
                        <div class='p-2  border border-gray-600 text-sm font-normal col-start-{{24/$colSpan+1}} col-span-{{12 - 24/$colSpan}} text-left'>
                            <x-controls.relationship-renderer id={{$id}} type={{$type}} colName={{$columnName}} modelPath={{$modelPath}} />
                        </div>
                        @break
                    @default
                    <span class='p-2  border border-gray-600 text-sm font-normal col-start-{{24/$colSpan+1}} col-span-{{12 - 24/$colSpan}} text-left'>{{$content}}</span>
                        
                @endswitch
                
            </div>
        </div>
    </div>
@else
    <div class='col-span-{{$colSpan}} grid'>
        <div class='grid grid-rows-1'>
            <div class='grid grid-cols-12 text-right '>
                <label class='p-2 border border-gray-600 text-base font-medium bg-gray-50 h-full w-full flex col-span-{{24/$colSpan}} items-center justify-end col-start-1'>{{$label}}</label>
                @if ($control == 'toggle')
                    <span class='p-2 border border-gray-600 flex justify-start items-center text-sm font-normal col-start-{{24/$colSpan+1}} col-span-{{12 - 24/$colSpan}} text-left'>{{$content == "1" ? "Yes" : "No"}}</span>
                @else
                    <span class='p-2 border border-gray-600 flex justify-start items-center text-sm font-normal col-start-{{24/$colSpan+1}} col-span-{{12 - 24/$colSpan}} text-left'>{{$content}}</span>
                @endif
            </div>
        </div>
    </div>
@endif
