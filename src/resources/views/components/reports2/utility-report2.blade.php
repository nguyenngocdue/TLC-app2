{{-- @dd($entityType); --}}
<div class="w-full p-2 lg:p-0  m-auto no-print">
    <div class="flex {{$class}}">
        <form action="{{$route}}" method="POST">
            @csrf
            <input type="hidden" name='action' value="updateReport2">
            <input type="hidden" name='block_title' value="{{$blockTitle}}">
            <input type="hidden" name="queriedData" value="{{ json_encode($queriedData) }}">
            <input type="hidden" name="configuredCols" value="{{ json_encode($configuredCols) }}">
            <x-renderer.button  htmlType='submit' title="Export this list to CSV ">
                <i class="fa-duotone fa-file-csv"></i> Export to CSV
            </x-renderer.button>
        <form>                
        {{-- <a href="{{$route}}" target="_blank">
            <x-renderer.button href="javascript:print()" title="Print this screen">
                <i class="fa-solid fa-print"></i>
            </x-renderer.button>
        </a> --}}
    </div>
</div>
