<div class="relative p-2">
    <div class="overflow-hidden h-5 text-xs flex rounded bg-gray-200">
        @foreach($dataSource as $line)
        <div style="width: {{$line['percent']}}" class="{{$classList}} bg-{{$line['color']}}-500">{{$line['label']}}</div>
        @endforeach
    </div>
</div>