@php
$selected = (!is_null(old($colName))) ? old($colName) * 1 : isset($currentEntity[$colName]) && $currentEntity[$colName] * 1;
@endphp

@if(count($dataSource) <= 1) <p class=' bg-white border border-gray-300 text-blue-400 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500'>Please check data of "{{$tableName}}" tbale</p>
    @else
    <select name='{{$colName}}' id="countries" class="form-select  bg-white border border-gray-300  text-sm rounded-lg block  mt-1  focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
        <option class="py-10" value="" selected>Select your option...</option>
        @foreach($dataSource as $data)
        <option class="py-1" value="{{$data->id}}" {{$selected  === $data->id * 1 ? "selected" : ""}} title="{{isset($data->description) ? "$data->description (#$data->id)" : "" }}" data-bs-toggle="tooltip">{{$data->name}}</option>
        @endforeach
    </select>
    @endif
    @error($colName)
    <span class="text-xs text-red-400 font-light" role="alert">
        <strong>{{$message}}</strong>
    </span>
    @enderror