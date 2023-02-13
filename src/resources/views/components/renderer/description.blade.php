@if($content instanceof \Illuminate\Database\Eloquent\Collection)
    <div class='col-span-{{$colSpan}} grid'>
        <div class='grid grid-row-1'>
            <div class='grid grid-cols-12 text-right '>
                <label class='p-2 border bg-gray-50 h-full w-full flex  col-span-{{24/$colSpan}} items-center justify-end col-start-1'>{{$label}}</label>
                @if($control === 'attachment')
                    @php
                        $value = $content->toArray();
                    @endphp
                    <div class='p-2  border col-start-{{24/$colSpan+1}} col-span-{{12 - 24/$colSpan}} text-left'>
                        <x-renderer.attachment2 name='attachment' :value="$value" destroyable={{false}} showToBeDeleted={{false}} showUploadFile={{false}} />
                    </div>
                @endif
                <span class='p-2  border col-start-{{24/$colSpan+1}} col-span-{{12 - 24/$colSpan}} text-left'>{{$content}}</span>
            </div>
        </div>
    </div>
@else
    <div class='col-span-{{$colSpan}} grid'>
        <div class='grid grid-row-1'>
            <div class='grid grid-cols-12 text-right '>
                <label class='p-2 border bg-gray-50 h-full w-full flex  col-span-{{24/$colSpan}} items-center justify-end col-start-1'>{{$label}}</label>
                <span class='p-2  border col-start-{{24/$colSpan+1}} col-span-{{12 - 24/$colSpan}} text-left'>{{$content}}</span>
            </div>
        </div>
    </div>
@endif
