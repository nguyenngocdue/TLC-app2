<div class="grid grid-cols-12 gap-2 text-center'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
    @foreach($dataSource as $key => $data)
    <div class="flex items-center mr-4 col-span-{{$span}} ">
        <input {{$selected*1 === $key*1 ? "checked":"checked"}} id="{{$key}}" name={{$colName}} type="checkbox" value="{{$key+1}}" class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
        <label for="{{$colName}}" class="py-3 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">{{$data->name}}</label>
    </div>
    @endforeach
</div>
