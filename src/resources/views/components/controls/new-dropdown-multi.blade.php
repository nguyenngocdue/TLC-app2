@php
$valDataSource = array_values($dataSource)[0];
$editColName = str_replace('()', '', $colName);
@endphp

<select multiple="multiple" name="{{$colName}}[]" id="select-dropdowm-multi-{{$editColName}}" class="min-h-[150px] max-h-[200px] border bg-white border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
    @foreach($valDataSource as $key => $data)
    @php
    $selected = is_null(old($colName)) ? isset($idsChecked) && in_array($data->id, $idsChecked) : in_array($data->id, old($colName));
    @endphp
    <option class="py-1  border-gray-200 border-b " {{ $selected ? "selected":""}} value="{{$data->id}}" title="{{isset($data->description) ? "$data->description (#$data->id)" : "" }}">{{$data->name}}</option>
    @endforeach
</select>
@include('components.feedback.alertValidation')


<script type="text/javascript">
    $('#select-dropdowm-multi-{{$editColName}}').select2({
        placeholder: "Select something..."
        , allowClear: true
    });

</script>
