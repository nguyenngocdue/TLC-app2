<div class="grid grid-cols-12 gap-{{$gap}} bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
    @php 
    $lastIndex = "anything";
    @endphp
    @foreach ($items as $item)
    @php
        if($groupBy){
            if(is_array($item) || is_object($item)){
                $index = strtoupper($item[$groupBy][0]);
                if ($index !== $lastIndex) {
                    $lastIndex = $index;
                    echo "<div class='col-span-12 flex'><span class='text-lg font-bold text-gray-600'>$index</span></div>";
                }
            }
        }
        $itemStr = is_array($item)? json_encode($item) : $item;
        // @dump($itemStr);
    @endphp
    
    <div class="bg-white-50 col-span-{{$colSpan}} flex align-center ">
        <x-renderer.avatar-name>{!!$itemStr!!}</x-renderer.avatar-name>
        {{-- The following line is find with component test, but buggy with manyIconParams --}}
        {{-- <{{$itemRenderer}}>{!!$itemStr!!}</{{$itemRenderer}}> --}}
    </div>
    @endforeach
</div>

