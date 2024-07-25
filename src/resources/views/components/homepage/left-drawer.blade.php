 @props(['title' => ""])
 <!-- drawer component -->
 <div id="drawer-left" class="fixed min-w-[200px] top-32 left-0 z-10 shadow-xl h-screen px-2 py-4 overflow-y-auto transition-transform -translate-x-full bg-white w-auto dark:bg-gray-800" tabindex="-1" aria-labelledby="drawer-left-label">
    <h5 id="drawer-left-label" class="inline-flex items-center mb-1 text-base font-semibold text-gray-500 dark:text-gray-400">
      {{$title}}</h5>
    <button type="button" data-drawer-hide="drawer-left" aria-controls="drawer-left" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 absolute top-2.5 right-2.5 inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" >
       <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
       <span class="sr-only">Close menu</span>
    </button>
    <div class="mb-6">
       {!!$slot!!}
    </div>
    
 </div>