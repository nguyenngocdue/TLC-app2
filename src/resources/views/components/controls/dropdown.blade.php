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
    let k2 = @json($colNames_ModelNames);
    let listenersJson = @json($listenersJson);

</script>
@endonce

<script type="text/javascript">
    var name = "{{$colName}}";
    var id = "select-dropdown-" + "{{$colName}}";
    var selected = "{{$selected}}";

    dropdownComponent({
        id
        , name
        , dataSource: k[k2[name]]
        , selected
    });

</script>


<script type="text/javascript">
    $('#select-dropdown-{{$colName}}').select2({
        placeholder: "Please select"
        , allowClear: true
    });

</script>
