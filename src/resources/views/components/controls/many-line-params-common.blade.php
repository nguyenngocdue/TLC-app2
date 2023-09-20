<script>
editableColumns['{{$table01Name}}'] = @json($editableColumns);
dateTimeControls['{{$table01Name}}'] = @json($dateTimeColumns);

tableObject['{{$table01Name}}'] = {
    // tableId:'{{$table01Name}}', 
    columns: editableColumns['{{$table01Name}}']
    , dateTimeControls: dateTimeControls['{{$table01Name}}']
    , eloquentFn: "{{$colName}}"
    , isOrderable: {{$isOrderable ? 1 : 0}}
    , showNo: {{$showNo ? 1 : 0}}
    , showNoR: {{$showNoR ? 1 : 0}}
    , tableDebugJs: {{$tableDebug ? 1 : 0}}
    , tableName: "{{$tableName}}"
};
tableObjectColName["{{$colName}}"] = {
    name:'{{$table01Name}}'
};
</script>