<select multiple name="{{$colName}}[]" id="countries" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
    @foreach($dataSource as $key => $data)
    @if ($action === 'edit')
    <option {{in_array($data->id, $idItems[$colName]) ? "selected":""}} value="{{$key+1}}">{{$data->name}}</option>
    @else
    <option value="{{$key+1}}">{{$data->name}}</option>
    @endif
    @endforeach
</select>
