@php
$idEntity = isset($currentEntity[$colName]) ? $currentEntity[$colName]*1 : null;
$selected = is_null(old($colName)) ? $idEntity : old($colName) * 1;
$valDataSource = array_values($dataSource)[0];
$colNameListenJson = array_column($listenersJson,'column_name');
// dump($colName , $colNameListenJson, $listenersJson);
@endphp

{{-- @dump($colName) --}}
<select name='{{$colName}}' id="select-dropdown-{{$colName}}" onchange="onChangeItem(value*1, {{$colName}})" class=" bg-white border border-gray-300 text-sm rounded-lg block mt-1 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
    <option class="py-10" value="" selected>Select your option...</option>
</select>

@include('components.feedback.alertValidation')

@once
<script>
    // get values from model
    let k1 = @json($dataListenTrigger);
    let listenersJson = @json($listenersJson);
</script>
@endonce

<script type="text/javascript">
    var name = "{{$colName}}";
    var idDOM = "select-dropdown-" + name;
    var selected = "{{$selected}}";

    var arrayKeysK = Object.keys(k1);
    var fieldName = getEntityName(name, arrayKeysK);
    var firstDropDownRender = dropdownComponent(idDOM, name, k1[fieldName], selecte);


    function onChangeItem(value, colName) {
        colName = colName.getAttribute("name");
        objListener = Object.values(listenersJson).find((item) => item.triggers === colName);
        const {
            listen_to_attrs,
            listen_to_fields,
            column_name
        } = objListener

        var fieldName = getEntityName(listen_to_fields, arrayKeysK);
        var dataListenTo = k1[fieldName];
        itemsDB = [];
        // console.log(dataListenTo, listen_to_fields, column_name);
        if (listen_to_fields === column_name) {
            itemsDB = dataListenTo.filter(ele => {
                return ele[listen_to_attrs] === value;
            })
        } else {
            dataListenTo.forEach(ele => {
                if (ele.id === value) {
                    idListener = ele[listen_to_attrs];
                    var _fieldName = getEntityName(column_name, arrayKeysK);
                    itemsDB = k1[_fieldName].filter(u => u.id === idListener);
                }
            })
        }
        strHtmlRender = itemsDB.map((item, index) => {
            return ` <option value="${item.id}">${item.name}</option>`
        })
        let eles = document.getElementById("select-dropdown-" + column_name);
        let headOption = listen_to_fields !== column_name ? [] : [`<option class="py-10" value="" selected>Select your option...</option>`]
        eles.innerHTML = strHtmlRender + headOption;
    }
</script>


<script type="text/javascript">
    $('#select-dropdown-{{$colName}}').select2({
        placeholder: "Please select",
        allowClear: true
    });
</script>