<div class="flex gap-4">
     <form action="{{ route('updateUserSettings') }}" method="post" class="w-1/2">
          @csrf
          @method('PUT')
          <input type="hidden" name="action" value="updateViewAllMatrix"/>
          <input type="hidden" name="_entity" value="{{$type}}"/>
          <div class="bg-white rounded w-full my-2 p-2">
               <div class="w-full my-1 grid grid-cols-10 gap-2 items-center">
                    <div class="col-span-1 text-right ">Workplace</div>
                    <div class="col-span-2">
                         <x-renderer.view-all-matrix-filter.WorkplaceFilter 
                         tableName="workplaces" 
                         name="workplace_id" 
                         id="workplace_id" 
                         allowClear="true"
                         {{-- typeToLoadListener="qaqc_wir"  --}}
                         selected="{{$viewportParams['workplace_id']}}"
                         />
                    </div>
                    <div class="col-span-2">
                         <x-renderer.button type='primary' htmlType="submit" icon="fa-sharp fa-solid fa-check">Apply Filter</x-renderer.button>
                    </div>
               </div>
          </div>
     </form>
     <div class="bg-white w-1/2 my-2 p1-1 rounded flex justify-between items-center gap-2">
          <div class="bg-white m1y-2 p-1 rounded flex justify-center1 items-center1 gap-2">
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
          <div class="bg-white m1y-2 p-1 rounded flex text-right justify-right items-end gap-2">
               
               <div>
                    <x-renderer.button class="border border-blue-700" href="{!! $dataSource['today'] !!}" icon='fa-duotone fa-repeat'>
                    {{$dataSource['todayLabel'] }}
                    </x-renderer.button>
               </div>
          </div>
     </div>

</div>