<div class="bg-white w-full my-2 p-1 rounded flex justify-between items-center">
    <div>
         {{-- <i class="w-5 h-full fa-regular fa-arrow-left"></i>  --}}
         <x-renderer.button class="border border-blue-700" href="{!! $dataSource['-1year'] !!}" title="-1 Year">-1Y</x-renderer.button>
         <x-renderer.button class="border border-blue-700" href="{!! $dataSource['-1month'] !!}" title="-1 Month">-1M</x-renderer.button>
         @if($viewportParams['mode'] == 'week')
              <x-renderer.button class="border border-blue-700" href="{!! $dataSource['-1week'] !!}" title="-1 Week">-1W</x-renderer.button>
              <x-renderer.button class="border border-blue-700" href="{!! $dataSource['+1week'] !!}" title="+1 Week">+1W</x-renderer.button>
         @endif
         <x-renderer.button class="border border-blue-700" href="{!! $dataSource['+1month'] !!}" title="+1 Month">+1M</x-renderer.button>
         <x-renderer.button class="border border-blue-700" href="{!! $dataSource['+1year'] !!}" title="+1 Year">+1Y</x-renderer.button>
         {{-- <i class="w-5 h-full fa-regular fa-arrow-right"></i>  --}}
    </div>
    <div>
          @php
               $isWeekMode = $viewportParams['mode'] !== 'week' ? 1 : 0;
               $isMonthMode = $viewportParams['mode'] !== 'month' ? 1 : 0;
               $classOfWeek = $isWeekMode ? "bg-gray-200 text-gray-400" : "bg-blue-700 text-white";
               $classOfMonth = $isMonthMode ? "bg-gray-200 text-gray-400" : "bg-blue-700 text-white";
          @endphp
        <x-renderer.button type="light" outline="true" disabled="{{!$isWeekMode}}" class="border border-blue-700 {{$classOfWeek}}" href="{!! $dataSource['weekView'] !!}" icon="fa-duotone fa-calendar-week">
              Week View
         </x-renderer.button>
         {{-- @else --}}
         <x-renderer.button type="light" outline="true" disabled="{{!$isMonthMode}}" class="border border-blue-700 {{$classOfMonth}}" href="{!! $dataSource['monthView'] !!}" icon="fa-duotone fa-calendar-days">
              Month View
         </x-renderer.button> 
    </div>
    <div>
         <x-renderer.button class="border border-blue-700" href="{!! $dataSource['today'] !!}" icon="fa-duotone fa-repeat">
              Reset to Today
         </x-renderer.button>
         {{-- @if($viewportParams['mode'] == 'month') --}}
         
         {{-- @endif --}}
    </div>
    
</div>