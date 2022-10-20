{{-- {{dd($currentEntity, $colName)}} --}}
@if(count($dataSource) <= 1) <p class=' bg-white border border-gray-300 text-blue-400 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500'>Please check data of "{{$tableName}}" tbale</p>
    @else
    <select name='{{$colName}}' id="countries" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
        @foreach($dataSource as $data)
        @if($action === "create")
        <option value="{{$data->id}}" title="{{$data->description}}" data-bs-toggle="tooltip">{{$data->name}}</option>
        @else
        <option value="{{$data->id}}" {{$currentEntity[$colName]*1 === $data->id*1 ? "selected" : ""}} title="{{$data->description}}" data-bs-toggle="tooltip">{{$data->name}}</option>
        @endif
        @endforeach
    </select>
    @endif
    @error($colName)
    <span class="text-xs text-red-400 font-light" role="alert">
        <strong id="{{$colName}}">{{$message}}</strong>
    </span>
    @enderror
