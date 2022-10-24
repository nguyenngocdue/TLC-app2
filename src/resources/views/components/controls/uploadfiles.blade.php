<div class="flex flex-col">
    @if ($action === "edit")
    <div class="relative mr-3 hidden h-12 w-12 md:block" title="{{$fileName}}">
        <a href={{  "$path.$url_media" }}>
            <img class="h-full w-full object-cover" src="{{ $path.$url_thumbnail }}" alt="{{$fileName}}" />
        </a>
    </div>
    <p class="text-gray-600 text-xs">{{$fileName}}</p>
    <input id="multiple_files" type="file" name={{$colName}}>
    @else
    <input id="multiple_files" type="file" name={{$colName}}>
    @endif
</div>
