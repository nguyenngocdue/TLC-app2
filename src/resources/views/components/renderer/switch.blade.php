<div>
    <label class="inline-flex relative items-center mr-5 cursor-pointer">
        <input type="checkbox" value="" class="sr-only peer" checked>
        <div class="w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-focus:ring-4 peer-focus:{{$color}} dark:peer-focus:ring-yellow-800 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:{{$color}}"></div>
        {!!$content ? "<span class='ml-3 text-sm font-medium text-gray-900 dark:text-gray-300'>$content</span>" : "" !!}
    </label>
</div>
