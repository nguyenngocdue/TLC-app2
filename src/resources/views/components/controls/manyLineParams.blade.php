<div class="grid grid-cols-12  gap-2 bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
    @foreach ($relatedItems as $relatedItem)
    <div class="items-cent1er bg-white-50 col-span-{{$colSpan}} flex align-center ">
        <x-renderer.avatar-name>{'name':'{{$relatedItem->name}}', 'position_rendered':'{{$relatedItem->position_rendered}}'}</x-renderer.avatar-name>
    </div>
    @endforeach
</div>
