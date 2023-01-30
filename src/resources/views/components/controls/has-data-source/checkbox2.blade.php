<div class="grid grid-cols-12 gap-2 bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
    @foreach($dataSource as $index => $item)
    <div class="items-center bg-white-50 col-span-{{$span}} flex align-center ">
        <label title="{{$item['description'] ?? ""}}">
            <input 
                type='checkbox' 
                name="{{$name}}[]" 
                value="{{$item['value']}}" 
                @checked(in_array($item['value'], $selected))
                />
            {{$item['label']}}
        </label>
    </div>
    @endforeach
</div>