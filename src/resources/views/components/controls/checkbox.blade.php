<div class=" grid-cols-12 gap-2 text-center'bg-gray-50 border border-white-300 bg-white text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
    @foreach($dataSource as $key => $data)
    <div class="flex items-center mr-4 col-span-4 ">
        @if ($action === 'edit')
        {{-- {{dd($idItems)}} --}}
        <input {{in_array($data->id, $idItems[$colName]) ? "checked":""}} name="{{$colName}}[]" value="{{$key+1}}" type="checkbox" id='{{$colName.$data->name}}' title="{{isset($data->description) ? "$data->description (#$data->id)" : "" }}" class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
        @else
        <input name="{{$colName}}[]" value="{{$key+1}}" type="checkbox" id='{{$colName.$data->name}}' title="{{isset($data->description) ? "$data->description (#$data->id)" : "" }}" class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
        @endif
        <label for='{{$colName.$data->name}}' id='{{1+$key.$data->name}}' class="py-3 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300" title="{{isset($data->description) ? "$data->description (#$data->id)" : "" }}">{{$data->name}}</label>
    </div>
    @endforeach
</div>
