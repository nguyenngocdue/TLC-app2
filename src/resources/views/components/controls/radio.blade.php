@if(count($dataSource) <= 0) <p class=' bg-white border border-gray-300 text-blue-400 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500'>The data source of "{{$tableName}}" table can be empty</p>
    @else
    <div class="grid grid-cols-12  gap-2 bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
        @foreach($dataSource as $key => $data)
        <div class="items-center bg-white-50 col-span-{{$span}} flex align-center ">
            @if($action === 'create')
            <input id="{{$key}}" type="radio" value="{{old($colName,$key+1)}}" name="{{$colName}}" class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 focus:ring-red-500 dark:focus:ring-red-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            @else
            <input id="{{$key}}" {{$currentEntity[$colName]*1 === $key*1+1 ? "checked":""}} type="radio" value="{{old($colName,$key+1)}}" name="{{$colName}}" class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 focus:ring-red-500 dark:focus:ring-red-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            @endif
            <label for="{{$key}}" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{$data->name}}</label>
        </div>
        @endforeach
    </div>
    @endif
    @error($colName)
    <span class="text-xs text-red-400 font-light" role="alert">
        <strong>{{$message}}</strong>
    </span>
    @enderror
