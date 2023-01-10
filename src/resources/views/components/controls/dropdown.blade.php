@php
$idEntity = isset($currentEntity[$colName]) ? $currentEntity[$colName]*1 : null;
$selected = is_null(old($colName)) ? $idEntity : old($colName) * 1;
// dd($dataSource);
// dd($dataListenTrigger);

@endphp

<div id="add-{{$colName}}">
</div>

@include('components.feedback.alertValidation')

@once
<script>
    let k = @json($dataListenTrigger);
    let k2 = @json($colNames_ModelNames);
    let listenersJson = @json($listenersJson);
    let colNames_idCurrentValue = {}
    let triggers_colNames = @json($triggers_colNames);
    let colNamesListener = Object.values(listenersJson).map((item) => item.column_name);

</script>
@endonce

<script type="text/javascript">
    var name = "{{$colName}}";
    var id = "select-dropdown-" + "{{$colName}}";
    var selected = "{{$selected}}";
    colNames_idCurrentValue[name] = "{{$idEntity}}" * 1;

    dropdownComponent({
        id
        , name
        , dataSource: k[k2[name]]
        , selected
        , disabled: false
        , title_field_name: "name"
        , disabled_field_name: "resigned"
    });

</script>


<script type="text/javascript">
    function formatState(state) {
        if (!state.id) {
            return state.text;
        }

        var text = state.text.split('#@#')
        var addText = text[1] ? '' + text[1] : '';
        var $state = $(
            `<div class="flex justify-between px-1">
                <span>${text[0]}</span><span>${addText}</span>
            </div>
            `
        );
        return $state;
    };

    $('#select-dropdown-{{$colName}}').select2({
        placeholder: "Please select"
        , allowClear: true
        , templateResult: formatState
    });

    // Show items when edit status
    fixValueElement("{{$colName}}")

</script>
