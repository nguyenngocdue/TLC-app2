@if(isset($actionBtnList['exportSCV']) && $actionBtnList['exportSCV'])
    <x-renderer.button class="mr-1" type="secondary" outline=true href="{!!$routeExportCSV!!}" target="_blank">
        <i class="fa-duotone fa-file-csv"></i> Export to CSV
    </x-renderer.button>
@endif

{{-- @if(isset($actionBtnList['printTemplate']) && $actionBtnList['printTemplate'])
    <x-renderer.button class="mr-1" type="secondary" htmlType="submit" outline=true>
        <i class="fa-duotone fa-print"></i> Print Template with extra empty lines
    </x-renderer.button>
@endif --}}

@if(isset($actionBtnList['approveMulti']) && $actionBtnList['approveMulti'])
    <div id="divApproveMulti"></div>
@endif


@if(isset($actionBtnList['sendManyRequest']) && $actionBtnList['sendManyRequest'])
    <div id="divSendManyRequest{{$matrixKey}}">
        {{-- <x-renderer.button disabled class="mr-1" type="secondary" outline=true>
            <i class="fa-duotone fa-file-csv"></i> Send Sign-off Request
        </x-renderer.button> --}}
        <button disabled component="placeholder" class="bg-blue-300 rounded p-2 text-white font-semibold">Send Sign-off Request</button>
    </div>
@endif
