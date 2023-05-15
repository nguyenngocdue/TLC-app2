<div class="relative p-2">
    <div class="overflow-hidden h-5 text-xs flex rounded bg-gray-200">
        @foreach($dataSource as $line)
            <div style="width: {{$line['percent']}}" 
                class="{{$classList}} {{$line['classList'] ?? ""}} bg-{{$line['color']}}-500"
                title="{{$line['title'] ?? ''}}"
                @isset($line['id']) id="progress_{{$line['id']}}" @endisset
                >{{$line['label']}}
            </div>
        @endforeach
    </div>
</div>