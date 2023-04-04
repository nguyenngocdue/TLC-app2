@php
// dd($column, $name);
$title = isset($column['title']) ? $column['title'] : $name;
@endphp
<form name="{{$formName}}" method="GET" class="mb-2">
    <div class="grid grid-row-1 w-full">
        <div class="grid grid-cols-12 gap-4 items-end">
            <div class="col-span-3">
                <input type="hidden" name='_entity' value="{{ $entity }}">
                <input type="hidden" name='action' value="updateReport{{$typeReport}}">
                <input type="hidden" name='type_report' value="{{$typeReport}}">
                <x-reports.dropdown6 title="{{$title}}" name="{{$name}}" formName="{{$formName}}" :dataSource="$dataRender" :itemsSelected="$itemsSelected" />
            </div>
        </div>
    </div>
</form>
