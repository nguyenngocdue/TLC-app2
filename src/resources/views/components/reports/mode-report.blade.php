@php
// dd($column, $name);
$title = isset($column['title']) ? $column['title'] : $name;
@endphp
<form name="{{$formName}}" method="GET" class="flex  items-end w-72 px-2 ">
    <input type="hidden" name='_entity' value="{{ $entity }}">
    <input type="hidden" name='action' value="updateReport{{$typeReport}}">
    <input type="hidden" name='type_report' value="{{$typeReport}}">
    <input type="hidden" name='user_id' value="{{$userId}}">
    <x-reports.dropdown6 title="{{$title}}" name="{{$name}}" formName="{{$formName}}" :dataSource="$dataRender" :itemsSelected="$itemsSelected" />
</form>
