@php
// dd($itemsSelected)
@endphp
<form name="{{$formName}}" method="GET" class="flex flex-row-reverse">
    <input type="hidden" name='_entity' value="{{ $entity }}">
    <input type="hidden" name='action' value="updateReport{{$typeReport}}">
    <input type="hidden" name='type_report' value="{{$typeReport}}">
    <input type="hidden" name='user_id' value="{{$userId}}">
    <x-reports.dropdown6 title="{{$title}}" name="{{$name}}" formName="{{$formName}}" :dataSource="$dataRender" :itemsSelected="$itemsSelected" />
</form>
