@php
    $dataFilter = [
        'report-prod_sequence_020' => 'Production Routing Links were created',
        'report-prod_sequence_070' => "Production Routing Links weren't created",
    ];

    $params = [
        'form_type' => 'updateParamsReport',
        'project_id' => '8',
        'picker_date' => '30/09/2023',
        'sub_project_id' => '107',
        'prod_routing_id' => '49',
        'forward_to_mode' => 'report-prod_sequence_020',
    ];
@endphp





<div class="grid grid-cols-12 gap-1">
    <div class="col-span-2">
        <div class="no-print justify-end pl-4 pt-5">
            <x-reports.dropdown8 title="Mode" name="forward_to_mode" routeName="report-prod_sequence_020"
                :allowClear="false" :dataSource="$dataFilter" typeReport="reports" entity="prod_sequences" modeOption="020"
                :itemsSelected="$params" />
        </div>
    </div>

    <div class="col-span-10">
        <div class="px-4">
            ABCABCABCABCABCABCABCABC
        </div>
    </div>
</div>
