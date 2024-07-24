<section id="what-it-does" class="relative w-full bg-black-tlc" data-carousel="slide" style="scroll-margin-top: 90px;">
    <!-- Carousel wrapper -->
    <div class="relative min-h-[50vh] sm:min-h-[60vh] overflow-hidden md:min-h-[85vh]">
        @foreach($images as $key => $image)
        <div class="hidden duration-700 ease-in-out" data-carousel-item>
            <div class="z-40">
                <img src="{{$image}}" class="absolute block w-full lg:w-4/5 -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="..."
                    onmouseover="FlowbiteInstances.getInstance('Carousel', 'what-it-does').pause()"        
                    onmouseout="FlowbiteInstances.getInstance('Carousel', 'what-it-does').cycle()"        
                >
                <div class="shadow-1 bg-opacity-70 text-lg text-blue-900 absolute border-3 rounded-lg top-[80%] ml-[1%] w-[98%] xl:left-1/4 xl:w-1/2 p-2 bg-white">
                    <p class="text-xl font-bold text-shadow-md">{{$contents[$key]["title"]}}</p>
                    <p class="hidden md:block">{{$contents[$key]["description"]}}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <!-- Slider indicators -->
    <div class="absolute z-30 flex -translate-x-1/2 bottom-5 left-1/2 space-x-3 rtl:space-x-reverse">
        @foreach($images as $key => $image)
            <button 
                type="button" 
                class="w-3 h-3 rounded-full bg-slate-400" 
                aria-current="{{$key==0 ? "true" : "false"}}" 
                aria-label="Slide {{$key}}" 
                data-carousel-slide-to="{{$key}}"
                ></button>
        @endforeach
        
    </div>
    <!-- Slider controls -->
    <button 
        type="button" 
        class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" 
        data-carousel-prev
        >
        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
            <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
            </svg>
            <span class="sr-only">Previous</span>
        </span>
    </button>
    <button 
        type="button" 
        class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" 
        data-carousel-next
        >
        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
            <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
            </svg>
            <span class="sr-only">Next</span>
        </span>
    </button>
</section>