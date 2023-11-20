<style type="text/css">
    #drawer-left {
        transition: left 0.7s ease-linear;
    }
</style>

<div class="fixed left-0 z-20">
        <div class="text-center transform-none">
            <button class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800" onclick="toggleDrawer()" type="button" data-drawer-target="drawer-left" data-drawer-show="drawer-left" aria-controls="drawer-contact" data-drawer-body-scrolling="true" data-drawer-backdrop="false">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
</div>

<div id="drawer-left" class="hidden fixed min-w-[200px] left-100 z-20 shadow-xl h-screen px-2 py-4 overflow-y-auto  bg-white w-auto dark:bg-gray-800 top-[340px] transition-transform duration-[3000ms] ease-in-out " tabindex="-1" aria-labelledby="drawer-left-label" aria-modal="false" role="dialog" >
    <h5 id="drawer-left-label" class="inline-flex items-center mb-1 text-base font-semibold text-gray-500 dark:text-gray-400">
      Table of Content</h5>
    <button type="button" data-drawer-hide="drawer-left" aria-controls="drawer-left" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 absolute top-2.5 right-2.5 inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" onclick="toggleDrawer()">
       <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
       <span class="sr-only">Close menu</span>
    </button>
    <div class="mb-6">
       <div class="overflow-y-auto">
                    <div class="block ml-2">
						@foreach($dataSource as $id => $name)
							<div class="pr-1 py-1 text-sm  focus:ring-4 hover:border-b hover:border-gray-200">
								<a href="#page{{$id}}" class="text-blue-500 font-roboto font-bold">{{$name}}</a>
							</div>
						@endforeach
                    </div>
        </div>
    </div>
    
 </div>

 <script>
	function toggleDrawer() {
		var drawer = document.getElementById('drawer-left');
		drawer.classList.toggle('hidden');
        //drawer.style.left = drawer.classList.contains('hidden') ? '-200px' : '0';
	}
 </script>

 <script>
    var drawer = document.getElementById('drawer-left');

    // Add scroll event listener
    window.addEventListener('scroll', function() {
        // Get the current scroll position
        var scrollPosition = window.scrollY || document.documentElement.scrollTop;

        // Check if the scroll position is greater than or equal to one-third of the window height
        if (scrollPosition >= window.innerHeight / 3.5) {
            // Set the top property of the drawer to 70px
            drawer.style.top = '70px';
        } else {
            // Set the top property of the drawer to the initial value (here it's top-[340px])
            drawer.style.top = '340px';
        }
    });
 </script>
 