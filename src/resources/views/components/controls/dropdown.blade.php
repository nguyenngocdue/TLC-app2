@php
$idEntity = isset($currentEntity[$colName]) ? $currentEntity[$colName]*1 : null;
$selected = is_null(old($colName)) ? $idEntity : old($colName) * 1;
$valDataSource = array_values($dataSource)[0];
$colNameListenJson = array_column($listenersJson,'column_name');
// dump($colName , $colNameListenJson, $listenersJson);
@endphp
@if(count($valDataSource) <= 0) <p class=' bg-white border border-gray-300 text-orange-400 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500'>DataSource is empty ("{{array_keys($dataSource)[0]}}").</p>
    @else
    @if(!in_array($colName, $colNameListenJson))
    <select name='{{$colName}}' id="select-dropdown-{{$colName}}" onchange="onChangeItem(value*1, {{$colName}}, {{$valDataSource}})" class="  bg-white border border-gray-300  text-sm rounded-lg block  mt-1  focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
        <option class="py-10" value="" selected>Select your option...</option>

        @foreach($valDataSource as $data)
        @php
        $title = isset($data->description) ? "$data->description (#$data->id)" : "" ;
        $idStr = Str::makeId($data->id, true);
        $_label = ($data->name ?? "Nameless")." ($idStr)";
        @endphp
        <option class="py-1" value="{{$data->id}}" @selected($selected===$data->id * 1) title="{{$title}}" data-bs-toggle="tooltip">{{$_label}}</option>
        @endforeach
    </select>
    @endif
    @endif

    {{-- If dropdown has listeners --}}
    @if(in_array($colName, $colNameListenJson))
    @php
    $listenerItem = [];
    foreach ($listenersJson as $value) {
        if (array_search($colName,array_values($value))){
        $listenerItem = $value;
        break;
    }
    };
    $idDomListener = $listenerItem['triggers'].$listenerItem['column_name'];
    // dump($listenerItem, $listenerName, $idDomListener);
    @endphp
    <select name='{{$colName}}' id="{{$idDomListener}}" class=" bg-white border border-gray-300  text-sm rounded-lg block  mt-1  focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
        <option class="py-10" value="" selected>Select your option...</option>
    </select>

    @once
    
    <script>
        const dataSource = [
            {id:1, title: "Hello", suffix: "#123", disabled: true},
            {id:1, title: "Hello", suffix: "#123"},
            {id:1, title: "Hello", suffix: "#123"},
        ]
        const Dropdown = ({id, name, dataSource, selected, onChange}) => {

        }
        // get values from model
        let k1 = @json($dataListenTrigger);
        let k2 = @json($dataListenToField);
        let listenersJson = @json($listenersJson);

    </script>
    @endonce

    <script type="text/javascript">
        $("#{{$idDomListener}}").select2({
            placeholder: "Please select"
            , allowClear: true
        });
        indexName = "{{$colName}}".includes('user') ? "users" : "{{$colName}}";

        strHtmlListener = k1[indexName].map((item, index) => {
            checkSelected = @json($selected) === item.id ? "selected" : "";
            return ` <option ${checkSelected} value=${item.id}>${item.name}</option>`
        })

        eleTriggers = document.getElementById("{{ $idDomListener}}");
        if (eleTriggers !== null) eleTriggers.innerHTML += strHtmlListener;
        // console.log(eleTriggers);
        function onChangeItem(value, colName, valDataSource) {

            colName = colName.getAttribute("name");
            objListener = Object.values(listenersJson).find((item) => item.triggers === colName);
            // console.log('objListener', listenersJson, colName)
            const {listen_to_attrs,listen_to_fields,column_name } =  objListener

            usersfield = listen_to_fields.includes('user') ? "users" : listen_to_fields;
            dataListenTo = Object.values(k2[usersfield]);
            // console.log('dataListenTo', dataListenTo, listen_to_fields, column_name);

            itemsDB = [];
            if (listen_to_fields === column_name) {
                itemsDB = dataListenTo.filter(ele => {
                    return ele[listen_to_attrs] === value;
                })
            } else {
                dataListenTo.forEach(ele => {
                    if (ele.id === value) {
                        idListener = ele[listen_to_attrs];
                        key = column_name.includes('user') ? "users" : column_name;
                        itemsDB = k1[key].filter(u => u.id === idListener);
                    }
                })
            }
            strHtmlRender = itemsDB.map((item, index) => {
                return ` <option value="${item.id}">${item.name}</option>`
            })
            let eles = document.getElementById(colName + column_name);
            let headOption = listen_to_fields !== column_name ? [] : [`<option class="py-10" value="" selected>Select your option...</option>`]
            eles.innerHTML = strHtmlRender + headOption;
        }

    </script>
    @endif


    @include('components.feedback.alertValidation')
    <script type="text/javascript">
        $('#select-dropdown-{{$colName}}').select2({
            placeholder: "Please select"
            , allowClear: true
        });

    </script>
