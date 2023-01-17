@php
$idEntity = isset($currentEntity[$colName]) ? $currentEntity[$colName]*1 : null;
$selected = is_null(old($colName)) ? $idEntity : old($colName) * 1;
// dd($dataSource);
// dd($dataListenTrigger);

@endphp

<div id="add-{{$colName}}">
</div>

@include('components.feedback.alert-validation')

@once
<script>
    let k = @json($dataListenTrigger);
    let k2 = @json($colNames_ModelNames);
    let listenersJson = @json($listenersJson);
    let colNames_idsCurrentValue = {}
    let triggers_colNames = @json($triggers_colNames);
    let colNamesListener = Object.values(listenersJson).map((item) => item.column_name);

</script>
@endonce

<script type="text/javascript">
    var name = "{{$colName}}";
    var id = "select-dropdown-" + "{{$colName}}";
    var selected = "{{$selected}}";
    colNames_idsCurrentValue[name] = "{{$idEntity}}" * 1;
    var filterColumn = Object.keys(@json($byFilter));
    var filterValue = Object.values(@json($byFilter));

    var dataSource = k[k2[name]];
    var newDataSource = filterColumn.length && filterValue.length ? dataSource.filter(item => item[filterColumn] * 1 === filterValue * 1) : dataSource;

    var onChangedItems = (value, colName) => {
        colName = colName.getAttribute("name");
        objListener = Object.values(listenersJson).find((item) => item.triggers === colName);
        if (typeof objListener === 'undefined') {
            fixValueElement(colName)
            return false
        };
        const {
            listen_to_attrs
            , listen_to_fields
            , column_name
        } = objListener

        let dataListenTo = k[k2[listen_to_fields]];
        itemsDB = [];
        if (listen_to_fields === column_name) {
            itemsDB = dataListenTo.filter(ele => {
                return ele[listen_to_attrs] === value;
            })
        } else {
            dataListenTo.forEach(ele => {
                if (ele.id === value) {
                    itemsDB = k[k2[column_name]].filter(u => u.id === ele[listen_to_attrs]);
                    return;
                }
            })
        }
        strHtmlRender = itemsDB.map((item) => {
            return ` <option value="${item.id}">${item.name}</option>`
        })
        // console.log(itemsDB)
        let ele = document.getElementById("select-dropdown-" + column_name);
        let optHolder = itemsDB.length < 2 ? [] : [`<option class="py-10" value="" selected >Select your option...</option>`];
        let headOption = listen_to_fields !== column_name ? [] : optHolder
        ele.innerHTML = strHtmlRender + headOption;

        fixValueElement(colName)
    }
    var onLoadItems = ({
        name
        , dataSource
    }) => {
        objListener = Object.values(listenersJson).find((item) => item.column_name === name);
        if (typeof objListener !== 'undefined' && selected !== '') {
            const {
                listen_to_attrs
                , listen_to_fields
            } = objListener
            if (colNamesListener.includes(name)) {
                itemsDB = [];
                var idTrigger = colNames_idsCurrentValue[triggers_colNames[name]]

                if (listen_to_fields === name) {
                    itemsDB = dataSource.filter(ele => {
                        if (typeof idTrigger !== 'undefined') {
                            return ele[listen_to_attrs] === idTrigger;
                        }
                    })
                } else {
                    itemsDB = [dataSource.find(ele => ele.id === selected * 1)]
                }
                return itemsDB;
            }

        };
        return dataSource;
    }

    dropdownComponent({
        id
        , name
        , dataSource: newDataSource
        , selected
        , disabled: false
        , title_field_name: "name"
        , disabled_field_name: "resigned"
        , onChanged: onChangedItems
        , onLoad: onLoadItems
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

    //In onLoad
    fixValueElement(name)

</script>
