<form action="{{ route('updateUserSettings') }}" method="post">
     @csrf
     @method('PUT')
     <input type="hidden" name="action" value="updateViewAllMatrix"/>
     <input type="hidden" name="_entity" value="{{$type}}"/>
     <div class="bg-white rounded w-full my-2 p-2">
         <div class="w-full my-1 grid grid-cols-12 gap-2">
           
             <div class="col-span-3">
                 Workplace:
                 <x-calendar.sidebar-filter-workplace
                     tableName="workplaces" 
                     name="workplace_id[]" 
                     id="workplace_id" 
                     multiple="true"
                     {{-- typeToLoadListener="qaqc_wir"  --}}
                     :selected="$viewportParams['workplace_id']"
                     />
             </div>
             
         </div>
         <x-renderer.button type='primary' htmlType="submit" icon="fa-sharp fa-solid fa-check">Apply Filter</x-renderer.button>
     </div>
 </form>