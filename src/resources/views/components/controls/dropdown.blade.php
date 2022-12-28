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
    <select name='{{$colName}}' id="select-dropdown-{{$colName}}" onchange="onChangeItem(value*1, {{$colName}})" class="  bg-white border border-gray-300  text-sm rounded-lg block  mt-1  focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
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
    @endif
    @endif

    @php
    $idTarget = '';
    @endphp
    @if(in_array($colName, $colNameListenJson))
    @php
    $indexValListen = array_values(array_filter($listenersJson, fn($item) => $item['column_name'] === $colName));
    $idTarget = $indexValListen[0]['target']."+1";
    dump($idTarget);
    @endphp
    <select id="{{$idTarget}}" class=" bg-white border border-gray-300  text-sm rounded-lg block  mt-1  focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
    </select>
    @endif

    @include('components.feedback.alertValidation')
    <script type="text/javascript">
        $('#select-dropdown-{{$colName}}').select2({
            placeholder: "Please select"
            , allowClear: true
        });

        var idTarget = @json($idTarget);
        $('#ddepartment_1+1').select2({
            placeholder: "Please select"
            , allowClear: true
        });

        var allDataTargtet = @json($dataUsers);
        var allTextHTMLDataTatrget = allDataTargtet.map((item, index) => {
            return ` <option  value=${item.id}>${item.full_name}</option>`
        })


        var eles = document.getElementById(idTarget);
        if (eles !== null) {
            eles.innerHTML = allTextHTMLDataTatrget;
        }

        function onChangeItem(value, colName) {
            var targetUserArray = [];
            colName = colName.getAttribute("name");
            targetUser = allDataTargtet.filter((ele, index) => {
                return ele.department === value;
            })

            var textHTML = targetUser.map((item, index) => {
                return ` <option  value=${item.id}>${item.full_name}</option>`
            })
            var els = document.getElementById(colName + "+1");
            els.innerHTML = textHTML;
        }

    </script>
