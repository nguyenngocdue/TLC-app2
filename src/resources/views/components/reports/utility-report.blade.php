 <div class="w-full lg:w-1/3 p-2 lg:p-0 items-center mb-4 m-auto">
     <div class="lg:flex lg:justify-center lg:gap-2">
         <x-renderer.button title="Export this list to CSV">
             <a href="{{route($routeName.'_ep.exportCSV')}}" target="_blank">
                 <i class="fa-duotone fa-file-csv"></i>
             </a>
         </x-renderer.button>
         <x-renderer.button title="Export this list to CSV">
             <a href="{{route($routeName.'_ep.exportCSV')}}" target="_blank">
                 <i class="fa-solid fa-print"></i>
             </a>
         </x-renderer.button>
     </div>
 </div>
