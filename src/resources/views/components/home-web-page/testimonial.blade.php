<section id="testimonials" class="items-center bg-black py-12">
    <div class="flex items-center justify-center">
        <h2 class="text-4xl text-center md:text-6xl font-bold text-yellow-600 py-5 lg:py-14">We help solve problems in real time</h2>
    </div>
    <div id="controls-carousel" class="relative w-full" data-carousel="slide">
    <!-- Carousel wrapper -->
        <div class="relative h-72 overflow-hidden rounded-lg">
            @foreach($dataSource as $item)
                <div class="hidden duration-700 ease-in-out" data-carousel-item="active">
                    <div class="flex justify-center items-center h-full">
                        <div class="text-white text-center mx-10 md:mx-80">
                            <x-home-web-page.rating rating="{{$item['rating']}}"/>
                            <h4 class="py-2 text-base md:text-xl font-bold">{{$item["title"]}}</h4>
                            <p class="text-sm md:text-base">{{$item["content"]}}</p>
                            <span class="py-2 text-base md:text-xl font-bold">{{$item["owner"]}}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <!-- Slider controls -->
        <button type="button" class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                </svg>
                <span class="sr-only">Previous</span>
            </span>
        </button>
        <button type="button" class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <span class="sr-only">Next</span>
            </span>
        </button>
    </div>
    {{-- <div class="flex justify-center py-5 lg:py-10">
        <x-home-web-page.button>Book a Demo</x-home-web-page.button>
    </div> --}}
</section>