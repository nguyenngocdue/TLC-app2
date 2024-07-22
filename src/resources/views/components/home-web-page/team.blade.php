<section id="who-use-it" class="items-center py-20 lg:py-30" style="scroll-margin-top: 90px;">
    <div class="flex items-center justify-center">
        <h2 class="text-4xl md:text-6xl font-bold">Who Use It</h2>
    </div>
    <div class="m-10 xl:w-3/5 w-full mx-auto grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @foreach($dataSource as $item)
            <figure class="relative mx-auto max-w-sm w-44 transition-all duration-300 cursor-pointer filter grayscale hover:grayscale-0">
                <a href="#">
                    <img class="rounded-2xl" src="{{$item['src']}}" alt="image description">
                </a>
                <figcaption class="absolute px-4 w-full text-lg text-white bottom-6">
                    <div class="flex justify-center items-center text-center">
                        <p class="text-2xl text-white font-bold text-shadow-xl">{{$item['name']}}</p>
                    </div>
                </figcaption>
            </figure>
        @endforeach
    </div>
</section>