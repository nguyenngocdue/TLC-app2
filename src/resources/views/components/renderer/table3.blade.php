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
                'showPaginationTop': @json($showPaginationTop),
                'showPaginationBottom': @json($showPaginationBottom),

                'tableHeader': @json($header),
                'tableFooter': @json($footer),
            },
        }
        console.log("Initilizing table {{$tableName}}", {{$tableName}})
        {{$tableName}}_Object = new EditableTable3(params)
        {{$tableName}}_Object.render()
    });
</script>
