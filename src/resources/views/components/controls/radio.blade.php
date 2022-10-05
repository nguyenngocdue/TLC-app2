<div class="grid grid-cols-12 gap-2 text-center'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
    @foreach($dataSource as $key => $data)
    <div class="flex items-center mr-4 col-span-{{$span}} ">
        <input {{$currentUser->workplace*1 === $key*1+1 ? "checked":""}} id="{{$key}}" type="radio" value="{{$key+1}}" name="{{$colName}}" class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 focus:ring-red-500 dark:focus:ring-red-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
        <label for="{{$colName}}" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{$data->name}}</label>
    </div>
    @endforeach
</div>
