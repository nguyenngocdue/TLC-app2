<div class="bg-white w-full my-2 p-1 rounded flex justify-between items-center gap-2">
     <div class="bg-white my-2 p-1 rounded flex justify-center1 items-center1 gap-2">
          <div>
               <x-renderer.button class="border border-blue-700" href="{!! $dataSource['-1year'] !!}">
                    {{$dataSource['-1yearLabel']}}
               </x-renderer.button>
          </div>
          <div class="mt-2">
              
               {{-- <x-renderer.button class="border border-blue-700" href="{!! $dataSource['selectedYear'] !!}"> --}}
                    {{$dataSource['selectedYearLabel'] }}
               {{-- </x-renderer.button> --}}
          </div>
          <div>
               <x-renderer.button class="border border-blue-700" href="{!! $dataSource['+1year'] !!}">
                    {{$dataSource['+1yearLabel']}}
               </x-renderer.button>
          </div>
     </div>
     <div class="bg-white my-2 p-1 rounded flex text-right justify-right items-end gap-2">
          <div>
               <x-renderer.button class="border border-blue-700" href="{!! $dataSource['today'] !!}" icon='fa-duotone fa-repeat'>
               {{$dataSource['todayLabel'] }}
               </x-renderer.button>
          </div>
     </div>
</div>