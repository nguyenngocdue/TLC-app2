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

            'tableConfig': {
                'tableDebug': {{$tableDebug ? "true" : "false"}},
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

                'showNo': @json($showNo),
            },
        }
        console.log("Initilizing table {{$tableName}}", {{$tableName}})
        {{$tableName}}_Object = new EditableTable3(params)
        {{$tableName}}_Object.render()
    });
</script>
