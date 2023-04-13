<div class="w-full lg:w-1/3 p-2 lg:p-0 items-center">
    <div class="lg:flex lg:justify-center lg:gap-2">
        <a href="{{route($type.'_ep.exportCSV')}}" target="_blank">
        <x-renderer.button title="Export this list to CSV">
                <i class="fa-duotone fa-file-csv"></i>
            </x-renderer.button>
        </a>
        <a href="{{route($type.'_qr.showQRCode')}}">
        <x-renderer.button title="Print this list QR Code">
                <i class="fa-duotone fa-qrcode"></i>
            </x-renderer.button>
        </a>
        <x-modal-settings type="{{$type}}"/>
    </div>
</div>