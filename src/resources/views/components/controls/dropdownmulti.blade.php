<select multiple name="{{$colName}}[]" id="countries" class=" min-h-[150px] max-h-[200px] border bg-white border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
    @foreach($dataSource as $key => $data)
    @if (!is_null(old($colName)))
    <option class="py-1  border-gray-200 border-b " {{in_array($data->id, old($colName)) ? "selected":""}} value="{{$key+1}}" title="{{isset($data->description) ? "$data->description (#$data->id)" : "" }}">{{$data->name}}</option>
    @else
    <option class="py-1  border-gray-200 border-b " {{isset($idItems[$colName]) && in_array($data->id, $idItems[$colName]) ? "selected":""}} value="{{$key+1}}" title="{{isset($data->description) ? "$data->description (#$data->id)" : "" }}">{{$data->name}}</option>
    @endif
    @endforeach
</select>
