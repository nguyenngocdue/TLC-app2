<form action="{{ route('updateUserSettings') }}" method="post" class="w1-1/2">
     @csrf
     @method('PUT')
     <input type="hidden" name="action" value="updateViewAllMatrix"/>
     <input type="hidden" name="_entity" value="{{$type}}"/>
     <div class="bg-white rounded w-full my-2 p-2">
          <div class="w-full my-1 grid grid-cols-10 gap-2 items-center">
               <div class="col-span-2">
                    Workplace
                    <x-renderer.view-all-matrix-filter.WorkplaceFilter 
                    tableName="workplaces" 
                    name="workplace_id" 
                    id="workplace_id" 
                    allowClear="true"
                    {{-- typeToLoadListener="qaqc_wir"  --}}
                    selected="{{$viewportParams['workplace_id']}}"
                    />
               </div>
               <div class="col-span-2">Year
                    <x-renderer.view-all-matrix-filter.YearFilter 
                    {{-- tableName="workplaces"  --}}
                    name="viewport_date" 
                    id="viewport_date" 
                    {{-- allowClear="true" --}}
                    selected="{{$viewportParams['viewport_date']}}"
                    />
               </div>
          </div>

               <div class="col-span-2">
                    <x-renderer.button type='primary' htmlType="submit" icon="fa-sharp fa-solid fa-check">Apply Filter</x-renderer.button>
               </div>
     </div>
</form>