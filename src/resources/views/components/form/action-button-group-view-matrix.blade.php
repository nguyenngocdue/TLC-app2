@if(isset($actionBtnList['exportSCV']) && $actionBtnList['exportSCV'])
    <x-renderer.button class="mr-1" type="secondary" outline=true href="{!!$routeExportCSV!!}" target="_blank">
        <i class="fa-duotone fa-file-csv"></i> Export to CSV
    </x-renderer.button>
@endif

@if(isset($actionBtnList['printTemplate']) && $actionBtnList['printTemplate'])
    <x-renderer.button class="mr-1" type="secondary" htmlType="submit" outline=true>
        <i class="fa-duotone fa-print"></i> Print Template with extra empty lines
    </x-renderer.button>
@endif

@if(isset($actionBtnList['approveMulti']) && $actionBtnList['approveMulti'])
    <div id="divApproveMulti"></div>
@endif


@if(isset($actionBtnList['sendManyRequest']) && $actionBtnList['sendManyRequest'])
    <div id="divSendManyRequest"></div>
@endif
