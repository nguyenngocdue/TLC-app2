<div class="relative p-2">
    <div class="overflow-hidden text-xs flex rounded bg-gray-200" style="height: {{$height}};">
        @foreach($dataSource as $line)
            @if($line['percent'] == "0%") @continue @endif
            {{-- <div style="width: {{$line['percent']}}" 
                class="{{$classList}} {{$line['classList'] ?? ""}} bg-{{$line['color']}}-500"
                title="{{$line['title'] ?? ''}}"
                @isset($line['id']) id="progress_{{$line['id']}}" @endisset
                >{{$line['label']}}
            </div> --}}
            
            <x-renderer.button 
                style="width: {{$line['percent']}}"
                class="{{$classList}} {{$line['classList'] ?? ''}} bg-{{$line['color']}}-300 text-{{$line['color']}}-700"
                id="progress_{{($line['id'] ?? '')}}" 
                size="none"
                title="{{$line['title'] ?? ''}}"
                keydownEscape="closeModal('{{$modalId}}')" 
                click="toggleModal('{{$modalId}}', {{($line['modalKey']??'unknown_modal_key')}})"
            >
            <span class='w-full'>{!! $line['label'] ?? '' !!}</span>
        </x-renderer.button>
        @endforeach
    </div>
</div>