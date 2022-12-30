@php
$idEntity = isset($currentEntity[$colName]) ? $currentEntity[$colName]*1 : null;
$selected = is_null(old($colName)) ? $idEntity : old($colName) * 1;
$valDataSource = array_values($dataSource)[0];
$colNameListenJson = array_column($listenersJson,'column_name');
// dump($colName , $colNameListenJson, $listenersJson);
@endphp
@if(count($valDataSource) <= 0) <p class=' bg-white border border-gray-300 text-blue-400 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500'>Table "{{array_keys($dataSource)[0]}}" is empty.</p>
    @else
    @if(!in_array($colName, $colNameListenJson))
    <select name='{{$colName}}' id="select-dropdown-{{$colName}}" onchange="onChangeItem(value*1, {{$colName}}, {{$valDataSource}})" class="  bg-white border border-gray-300  text-sm rounded-lg block  mt-1  focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
        <option class="py-10" value="" selected>Select your option...</option>

        @foreach($valDataSource as $data)
        @php
        $title = isset($data->description) ? "$data->description (#$data->id)" : "" ;
        $idStr = Str::makeId($data->id, true);
        $label = ($data->name ?? "Nameless")." ($idStr)";
        @endphp
        <option class="py-1" value="{{$data->id}}" @selected($selected===$data->id * 1) title="{{$title}}" data-bs-toggle="tooltip">{{$label}}</option>
        @endforeach
    </select>
    {{-- @dump($label); --}}
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
    $strIdname = "-trigger";
    $triggerName = $listenerItem['triggers'].$strIdname;
    @endphp
    <select name='{{$colName}}' id="{{$triggerName}}" class=" bg-white border border-gray-300  text-sm rounded-lg block  mt-1  focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
        <option class="py-10" value="" selected>Select your option...</option>
    </select>

    @once
    <script>
        let k1 = @json($dataListenTrigger);
        let k2 = @json($dataListenToField);
        let dataListenTrigger = @json($dataListenTrigger);

    </script>
    @endonce

    <script type="text/javascript">
        $("#{{$triggerName}}").select2({
            placeholder: "Please select"
            , allowClear: true
        });

        strHtmlListener = dataListenTrigger["{{$colName}}"].map((item, index) => {
            checkSelected = @json($selected) === item.id ? "selected" : "";
            return ` <option ${checkSelected} value=${item.id}>${item.name}</option>`
        })

        eleTriggers = document.getElementById("{{ $triggerName }}");
        if (eleTriggers !== null) eleTriggers.innerHTML += strHtmlListener;

        function onChangeItem(value, colName, valDataSource) {

            colName = colName.getAttribute("name");
            objListener = Object.values(@json($listenersJson)).find((item) => item.triggers === colName)
            fieldNameTrigger = objListener.listen_to_attrs;
            listenToFied = objListener.listen_to_fields;
            column_name = objListener.column_name;


            dataListenTo = Object.values(k2[listenToFied]);

            dataRender = [];
            if (listenToFied === column_name) {
                dataRender = dataListenTo.filter(ele => {
                    return ele[fieldNameTrigger] === value;
                })
            } else {
                dataListenTo.forEach(ele => {
                    if (ele.id === value) {
                        idUserListener = ele[fieldNameTrigger];
                        dataRender = k1[column_name].filter(u => u.id === idUserListener);
                    }
                })
            }
            strHtmlRender = dataRender.map((item, index) => {
                return ` <option value="${item.id}">${item.full_name}</option>`
            })
            let eles = document.getElementById(colName + "{{$strIdname}}");
            let headOption = listenToFied !== column_name ? [] : [`<option class="py-10" value="" selected>Select your option...</option>`]
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
