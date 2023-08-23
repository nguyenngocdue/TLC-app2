@if(isset($actionBtnList['exportSCV']) && $actionBtnList['exportSCV'])
    <x-renderer.button class="mr-1" type="secondary" outline=true href="{!!$routeExportCSV!!}" target="_blank" title="Export this list to CSV">
        <i class="fa-duotone fa-file-csv"></i> Export to CSV
    </x-renderer.button>
@endif

@if(isset($actionBtnList['printTemplate']) && $actionBtnList['printTemplate'])
    <x-renderer.button class="mr-1" type="secondary" htmlType="submit" outline=true title="Print mode multiple">
        <i class="fa-duotone fa-print"></i> Print Template with extra empty lines
    </x-renderer.button>
@endif

@if(isset($actionBtnList['approveMulti']) && $actionBtnList['approveMulti'])
    <x-renderer.button class="mr-1" type="secondary" htmlType="submit" outline=true title="Print mode multiple">
        <i class="fa-duotone fa-check-double"></i> Approve Selected Sheets
    </x-renderer.button>
@endif
