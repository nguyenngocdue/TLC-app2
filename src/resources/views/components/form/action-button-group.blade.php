{{-- <div class="w-full lg:w-1/3 p-2 lg:p-0 items-center"> --}}
    {{-- <div class="lg:flex lg:justify-center lg:gap-2"> --}}
        <x-renderer.button class="mr-1" type="secondary" outline=true href="{{route($type.'_ep.exportCSV')}}" target="_blank" title="Export this list to CSV">
            <i class="fa-duotone fa-file-csv"></i> Export to CSV
        </x-renderer.button>
        @if($showQrList6Button)
            <x-renderer.button class="mr-1" type="secondary" outline=true href="{{route($type.'_qr.showQRList6')}}" title="Print this list QR Code" icon="fa-duotone fa-qrcode">
               Print QR Code 
            </x-renderer.button>
        @endif
        <x-modal-settings type="{{$type}}"/>
    {{-- </div> --}}
{{-- </div> --}}