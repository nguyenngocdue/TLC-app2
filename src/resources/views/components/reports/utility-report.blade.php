<div class="w-full lg:w-1/3 p-2 lg:p-0 items-center m-auto no-print">
    <div class="lg:flex lg:justify-center lg:gap-2">
        <a href="{{route($routeName.'_ep.exportCSV')}}" target="_blank">
            <x-renderer.button title="Export this list to CSV">
                <i class="fa-duotone fa-file-csv"></i> Export to CSV
            </x-renderer.button>
        </a>
        <a href="{{route($routeName.'_ep.exportCSV')}}" target="_blank">
            <x-renderer.button href="javascript:print()" title="Print this screen">
                <i class="fa-solid fa-print"></i>
            </x-renderer.button>
        </a>
    </div>
</div>
