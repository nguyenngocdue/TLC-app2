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

</script>
@endonce

<script type="text/javascript">
    var name = "{{$colName}}";
    var id = "select-dropdown-" + "{{$colName}}";
    var selected = "{{$selected}}";

    dropdownComponent({
        id
        , name
        , dataSource: k[k2[name]][name]
        , selected
        , disabled: false
        , title_field_name: "name"
        , disabled_field_name: false
    });

</script>


<script type="text/javascript">
    function formatState(state) {
        if (!state.id) {
            return state.text;
        }

        var text = state.text.split('#@#')
        var $state = $(
            `<div class="flex justify-between px-1">
                <span>${text[0]}</span><span>${text[1]}</span>
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
    var eleSelected = document.getElementById("select2-select-dropdown-{{$colName}}-container");
    var value = eleSelected.innerText;
    var newValue = value.substring(0, value.indexOf('#@#'))
    eleSelected.innerHTML = newValue;

</script>
