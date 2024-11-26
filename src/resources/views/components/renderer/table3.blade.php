@once
<script>
var tableObjectColumns = {};
var tableObjectIndexedColumns = {};
</script>
@endonce

<div id="{{$tableName}}" component="editable-table-3" class='my-1'>
    <div class="border text-center rounded p-4 bg-gray-100">
        <i class="fa-duotone fa-spinner fa-spin text-green-500 mx-2"></i>Loading table {{$tableName}}...
    </div>
</div>

@php
    $users = \App\Models\User::query()
        ->select('id', 'full_name' , 'employeeid')
        ->where('resigned', 0)
        ->where('show_on_beta', 0)
        ->orderBy('full_name')
        ->get();
@endphp

<script>

    k = {...k, "users": @json($users),}
    k_by = {}

    tableData = {...tableData, "{{$tableName}}":  @json($dataSource)}
    tableColumns = {...tableColumns, "{{$tableName}}": @json($columns)}

    var {{$tableName}}_Object = null;
    $(document).ready(function() {
        const params = {
            'tableName': @json($tableName),
            'dataHeader': @json($dataHeader),

            'tableConfig': {
                'tableDebug': {{$tableDebug ? "true" : "false"}},
                'tableTrueWidth': {{$tableTrueWidth ? "true" : "false"}},

                'showPaginationTop': @json($showPaginationTop),
                'showPaginationBottom': @json($showPaginationBottom),

                'topLeftControl': @json($topLeftControl),
                'topCenterControl': @json($topCenterControl),
                'topRightControl': @json($topRightControl),

                'bottomLeftControl': @json($bottomLeftControl),
                'bottomCenterControl': @json($bottomCenterControl),
                'bottomRightControl': @json($bottomRightControl),

                'tableHeader': @json($header),
                'tableFooter': @json($footer),

                'showNo': {{($showNo??0) ? "true" : "false"}},
                'maxH': @json($maxH),

                'rotate45Width': @json($rotate45Width),
                'rotate45Height': @json($rotate45Height),

                // 'editable': {{($editable??0) ? "true" : "false"}},
                'classList': @json($classList),
                'animationDelay': @json($animationDelay),

                'rowHeight': @json($rowHeight),

                'orderable': {{($orderable??0) ? "true" : "false"}},
                'duplicatable': {{($duplicatable??0) ? "true" : "false"}},
                'deletable': {{($deletable??0) ? "true" : "false"}},

                'lineObjectModelPath': 'an-object-type',
                'showButton': @json($showButton),
            },
        }
        // console.log("Initializing table {{$tableName}}", {{$tableName}})
        {{$tableName}}_Object = new EditableTable3(params)
        {{$tableName}}_Object.render()
    });
</script>
