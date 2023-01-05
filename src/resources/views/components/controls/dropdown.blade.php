@php
$idEntity = isset($currentEntity[$colName]) ? $currentEntity[$colName]*1 : null;
$selected = is_null(old($colName)) ? $idEntity : old($colName) * 1;
@endphp

<div id="add-{{$colName}}">
</div>

@include('components.feedback.alertValidation')

@once
<script>
    let k = @json($dataListenTrigger);
    let arrayKeysK = Object.keys(k);
    let listenersJson = @json($listenersJson);

</script>
@endonce

<script type="text/javascript">
    var name = "{{$colName}}";
    var idDOM = "select-dropdown-" + "{{$colName}}";
    var selected = "{{$selected}}";

    var fieldName = getFieldNameInK(name, arrayKeysK);
    dropdownComponent({
        idDOM
        , name
        , dataSource: k[fieldName]
        , selected
    });

</script>


<script type="text/javascript">
    $('#select-dropdown-{{$colName}}').select2({
        placeholder: "Please select"
        , allowClear: true
    });

</script>
