{{-- @php
$valDataSource = array_values($dataSource)[0];
@endphp --}}
<div class="grid grid-cols-12  gap-2 bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
    @foreach($dataSource as $data)
    <div class="items-cent1er bg-white-50 col-span-{{$span}} flex align-center ">
        @php
        $selected = is_null(old($colName)) ? isset($idItems[$colName]) && in_array($data->id, $idItems[$colName]) : in_array($data->id, old($colName));
        @endphp
        <input {{ $selected ? "checked":""}} name="{{$colName}}[]" value="{{$data->id}}" type="checkbox" id='{{$label.$data->name}}' title="{{isset($data->description) ? "$data->description (#$data->id)" : "" }}" class="text-purple-600 form-checkbox focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
        <label for='{{$label.$data->name}}' class=" ml-2 text-sm font-medium text-gray-900 dark:text-gray-300" title="{{isset($data->description) ? "$data->description (#$data->id)" : "" }}">{{$data->name}}</label>
    </div>
    @endforeach
</div>
@include('components.feedback.alertValidation')
