<div class="flex flex-col">
    @if ($action === "edit")
    @foreach($infMedia as $key => $media)
    {{-- {{dd($media, $cateIdName, $colName, $cateIdName[[$media['category']][0]])}} --}}
    @if ($colName === $cateIdName[[$media['category']][0]])
    <div class="relative mr-3 hidden h-12 w-12 md:block" title="{{$media['filename']}}">
        <a href={{ $path.$media['url_media'] }}>
            <img class="h-full w-full object-cover" src="{{ $path.$media['url_thumbnail']}}" alt="{{$media['filename']}}" />
        </a>
    </div>
    <p class="text-gray-600 text-xs">{{$media['filename']}}</p>
    @endif
    @endforeach
    <input id="multiple_files" type="file" name={{$colName}}>

    @else
    <input id="multiple_files" type="file" name={{$colName}}>
    @endif
</div>
