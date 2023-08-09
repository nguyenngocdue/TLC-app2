@php
$route = $routeName ? route($routeName) : "";
@endphp
{{-- @dd($itemsSelected) --}}
<form action="{{$route}}" id="{{$entity}}" method="GET">
    <div class="grid grid-row-1 w-full">
        <div class="grid grid-cols-12 gap-4 items-end">
            <input type="hidden" name='_entity' value="{{ $entity }}">
            <input type="hidden" name='action' value="updateReport{{Str::ucfirst($typeReport)}}">
            <input type="hidden" name='type_report' value="{{$typeReport}}">
            <input type="hidden" name='mode_option' value="{{$modeOption}}">
            <input type="hidden" name='form_type' value="updateParamsReport">
            @php
            $s = $itemsSelected["sub_project"] ?? "";
            $s2 = $itemsSelected["prod_order"] ?? "";
            $s3 = $itemsSelected["check_sheet"] ?? "";
            $s4 = $itemsSelected["run_option"] ?? "";
            $s5 = $itemsSelected["qaqc_insp_tmpl"] ?? "";
            @endphp
            <div class="col-span-3">
                Checksheet Type
                <x-reports.modals.param-checksheet-type name='qaqc_insp_tmpl' selected='{{$s5}}' />
            </div>
            <div class="col-span-3">
                Sub Project
                <x-reports.modals.param-sub-project-id name='sub_project' selected='{{$s}}' />
            </div>
            <div class="col-span-3">
                Production Order
                <x-reports.modals.param-prod-order-id name='prod_order' selected='{{$s2}}' />
            </div>
            <div class="col-span-3">
                Checksheet
                <x-reports.modals.param-check-sheet name='check_sheet' selected='{{$s3}}' />
            </div>
            <div class="col-span-3">
                Run History Option
                <x-reports.modals.param-run-history-option name='run_option' selected='{{$s4}}' />
            </div>

        </div>
    </div>
    <div class="py-2">
        <x-renderer.button htmlType="submit" type="primary"><i class="fa-sharp fa-solid fa-check"></i> Apply Filter</x-renderer.button>
        <x-renderer.button htmlType="submit" click="resetFilter()" type="secondary"><i class="fa-sharp fa-solid fa-circle-xmark pr-1"></i>Reset</x-renderer.button>
    </div>
</form>

<script type="text/javascript">
    function resetFilter() {
        $('[id="' + "{{$entity}}" + '"]').append('<input type="hidden" name="form_type" value="resetParamsReport">')
    }

</script>
