@empty($dataSource)
<div class="flex text-center ml-10 mr-10 md:ml-36 md:mr-36 justify-center">
    <div class="items-center">
        <div class="relative mt-5 w-full">
            @foreach($dataSource as $key => $value)
                <x-controls.time-line-item2 :dataSource="$value" :props="$props"/>
            @endforeach                 
        </div>
    </div>
</div>
@else
<x-renderer.emptiness class="p-2" />
@endempty




