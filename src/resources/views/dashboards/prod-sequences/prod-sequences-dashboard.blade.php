<x-renderer.matrix-for-report-filter.prod_sequences
    type="prod_sequences"
    :viewportParams="$viewportParams"
    :filterDataSource="$filterDataSource"
    />

<x-renderer.matrix-for-report.prod_sequences 
    projectId={{$viewportParams['project_id']}}
    subProjectId={{$viewportParams['sub_project_id']}}
    prodRoutingId={{$viewportParams['prod_routing_id']}}
    allowOpen=0
    {{-- dateToCompare={{}} --}}
    {{-- sequenceModeId={{}} --}}
    {{-- prodDisciplineId={{}} --}}
    />