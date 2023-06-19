<div class="bg-white w-full my-2 flex justify-between items-center">
     <div>
          {{-- <i class="w-5 h-full fa-regular fa-arrow-left"></i>  --}}
          <x-renderer.button class="border border-blue-700" href="{!! $href[0] !!}">
            -1 Year
          </x-renderer.button>
          <x-renderer.button class="border border-blue-700" href="{!! $href[1] !!}">
          -1 Month
          </x-renderer.button>
          <x-renderer.button class="border border-blue-700" href="{!! $href[2] !!}">
          -1 Week
          </x-renderer.button>
     </div>
     <x-renderer.button class="border border-blue-700" href="{!! $href[3] !!}">
          <div class="font-semibold">Today</div>
     </x-renderer.button>
     <div>
          <x-renderer.button class="border border-blue-700" href="{!! $href[4] !!}">
               +1 Week
          </x-renderer.button>
          <x-renderer.button class="border border-blue-700" href="{!! $href[5] !!}">
               +1 Month 
          </x-renderer.button>
          <x-renderer.button class="border border-blue-700" href="{!! $href[6] !!}">
               +1 Year
          </x-renderer.button>
          {{-- <i class="w-5 h-full fa-regular fa-arrow-right"></i>  --}}
     </div>
</div>
<x-renderer.table 
          :columns="$columns"
          :dataSource="$dataSource"
          groupBy="name_for_group_by"
          groupByLength=2
          />