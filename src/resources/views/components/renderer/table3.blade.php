@once
<script>
var tableObjectColumns = {};
var tableObjectIndexedColumns = {};
</script>
@endonce

<div id="{{$tableName}}" >
    Loading table {{$tableName}}...
</div>

<script>
    var {{$tableName}}_Object = null;
    $(document).ready(function() {
        const params = {
            'tableName': @json($tableName),
            'columns': @json($columns),
            'dataSource': @json($dataSource),
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
            },
        }
        console.log("Initilizing table {{$tableName}}", {{$tableName}})
        {{$tableName}}_Object = new EditableTable3(params)
        {{$tableName}}_Object.render()
    });
</script>
