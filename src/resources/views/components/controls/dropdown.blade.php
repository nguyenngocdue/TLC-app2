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
            //TODO: Change to array_search
            $listenerItem = array_values(array_filter($listenersJson, fn($item) => $item['column_name'] === $colName));
            $strIdname = "-target";
            $targetName = $listenerItem[0]['target'].$strIdname;
        @endphp
        <select name='{{$colName}}' id="{{$targetName}}" class=" bg-white border border-gray-300  text-sm rounded-lg block  mt-1  focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
            <option class="py-10" value="" selected>Select your option...</option>
        </select>
        <script type="text/javascript">
            $("#{{$targetName}}").select2({
                placeholder: "Please select"
                , allowClear: true
            });
            dataUsers = @json($dataUsers);
            selected = @json($selected);
            //TODO: render ONCE
            k = @json($dataListenTo);

            strHtmlListener = dataUsers.map((item, index) => {
                checkSelected = selected === item.id ? "selected" : "";
                return ` <option ${checkSelected} value=${item.id}>${item.full_name}</option>`
            })

            eleListeners = document.getElementById("{{ $targetName }}");
            if (eleListeners !== null) eleListeners.innerHTML += strHtmlListener;

            function onChangeItem(value, colName, valDataSource) {
                //TODO: Render ONCE
                listenersJson = @json($listenersJson);
                colName = colName.getAttribute("name");
                objListener = Object.values(listenersJson).find((item) => item.target === colName)

                fieldNameTarget = objListener.listen_target
                listenTo = objListener.listen_to
                column_name = objListener.column_name

                // {listen_target,listen_to,column_name} = objListener

                dataListenTo = Object.values(k[listenTo]);

                //TODO: change var name to Filter result
                usersRender = [];
                if (listenTo === column_name) {
                    usersRender = dataListenTo.filter(ele => {
                        return ele[fieldNameTarget] === value;
                    })
                } else {
                    dataListenTo.forEach(ele => {
                        if (ele.id === value) {
                            idUserListener = ele[fieldNameTarget];
                            usersRender = dataUsers.filter(u => u.id === idUserListener);
                        }
                    })
                }
                strHtmlRender = usersRender.map((item, index) => {
                    return ` <option value="${item.id}">${item.full_name}</option>`
                })
                let eles = document.getElementById(colName + "{{$strIdname}}");
                //TODO: maybe remove head option
                let headOption = listenTo !== column_name ? [] : [`<option class="py-10" value="" selected>Select your option...</option>`]
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
