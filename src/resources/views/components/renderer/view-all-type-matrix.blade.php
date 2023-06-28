<div class="bg-white w-full my-2 p-1 rounded flex justify-between items-center">
     <div>
          {{-- <i class="w-5 h-full fa-regular fa-arrow-left"></i>  --}}
          <x-renderer.button class="border border-blue-700" href="{!! $href['-1year'] !!}">
            -1 Year
          </x-renderer.button>
          <x-renderer.button class="border border-blue-700" href="{!! $href['-1month'] !!}">
          -1 Month
          </x-renderer.button>
          @if($viewportMode == 'week')
               <x-renderer.button class="border border-blue-700" href="{!! $href['-1week'] !!}">
               -1 Week
               </x-renderer.button>
          @endif
     </div>
     <div>
          <x-renderer.button class="border border-blue-700" href="{!! $href['today'] !!}" icon="fa-duotone fa-calendar-day">
               Today
          </x-renderer.button>
          {{-- @if($viewportMode == 'month') --}}
          <x-renderer.button type="light" outline="{{$viewportMode !== 'week'}}" class="border border-blue-700" href="{!! $href['weekView'] !!}" icon="fa-duotone fa-calendar-week">
               Week View
          </x-renderer.button>
          {{-- @else --}}
          <x-renderer.button type="light" outline="{{$viewportMode !== 'month'}}" class="border border-blue-700" href="{!! $href['monthView'] !!}" icon="fa-duotone fa-calendar-days">
               Month View
          </x-renderer.button>
          {{-- @endif --}}
     </div>
     <div>
          @if($viewportMode == 'week')
               <x-renderer.button class="border border-blue-700" href="{!! $href['+1week'] !!}">
                    +1 Week
               </x-renderer.button>
          @endif
          <x-renderer.button class="border border-blue-700" href="{!! $href['+1month'] !!}">
               +1 Month 
          </x-renderer.button>
          <x-renderer.button class="border border-blue-700" href="{!! $href['+1year'] !!}">
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
          footer="{!! $footer !!}"
          {{-- tableTrueWidth="{{true}}" --}}
          />