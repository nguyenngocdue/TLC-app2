@if($actionBtnShowExportCsv)
    <x-renderer.button class="mr-1" type="secondary" outline=true href="{!!$routeExportCSV!!}" target="_blank" title="Export this list to CSV">
        <i class="fa-duotone fa-file-csv"></i> Export to CSV
    </x-renderer.button>
@endif

@if($actionBtnShowPrintButton)
    <x-renderer.button class="mr-1" type="secondary" htmlType="submit" outline=true title="Print mode multiple">
        <i class="fa-duotone fa-print"></i> Print
    </x-renderer.button>
@endif
