@php

@endphp



<div class="grid grid-cols-12 gap-1">
    <div class="col-span-2">
        <div class="no-print justify-end pl-4 pt-5">
            <x-reports.dropdown8 title="Mode" name="forward_to_mode" routeName="report-prod_sequence_020"
                :allowClear="false" :dataSource="$keysNames" typeReport="reports" entity="prod_sequences" modeOption="020"
                :itemsSelected="$params" />
        </div>
    </div>

    <div class="col-span-10">
        <div class="px-4">
            ABCABCABCABCABCABCABCABC
        </div>
    </div>
</div>
