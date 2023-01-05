@php
$idEntity = isset($currentEntity[$colName]) ? $currentEntity[$colName]*1 : null;
$selected = is_null(old($colName)) ? $idEntity : old($colName) * 1;
$valDataSource = array_values($dataSource)[0];
@endphp
@if(count($valDataSource) <= 0) <p class=' bg-white border border-gray-300 text-orange-400 text-sm rounded-lg p-2.5 '>DataSource is empty ("{{array_keys($dataSource)[0]}}").</p>
    @else
    <div class="grid grid-cols-12  gap-2 bg-white border border-gray-300 text-gray-900 text-sm rounded-lg f=  w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white ">
        @foreach($valDataSource as $key => $data)
        <div class="items-center bg-white-50 col-span-{{$span}} flex align-center ">
            <input id="{{$label.$data->id}}" {{ $selected === $data->id ? "checked":""}} type="radio" value="{{$data->id}}" name="{{$colName}}" title="{{isset($data->description) ? "$data->description (#$data->id)" : "" }}" class=" text-purple-600 form-radio focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
            <label for="{{$label.$data->id}}" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300" title="{{isset($data->description) ? "$data->description (#$data->id)" : "" }}">{{$data->name}}</label>
        </div>
        @endforeach
    </div>
    @endif
    @include('components.feedback.alertValidation')
