<div class="{{$colName === "avatar" ? "" :"hidden"}}">
    <div class="relative mr-3 hidden h-12 w-12 md:block" title="{{$fileName}}">
        <a href={{  "$path.$url_media" }} class="w-full flex-col">
            <img class="h-full w-full object-cover" src="{{ $path.$url_thumbnail }}" alt="{{$fileName}}" />
        </a>
    </div>
    <p class="text-gray-600 text-xs">{{$fileName}}</p>
</div>
<input class="block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="multiple_files" type="file" name="files[]">
