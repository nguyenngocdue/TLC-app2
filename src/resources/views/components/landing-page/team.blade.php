<section class="items-center py-20 lg:py-40">
    <div class="flex items-center justify-center">
        <h2 class="text-4xl md:text-6xl font-bold">WHO USES {{env('APP_NAME')}}</h2>
    </div>
    <div class="m-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
        @foreach($dataSource as $item)
            <figure class="relative max-w-sm transition-all duration-300 cursor-pointer filter grayscale hover:grayscale-0">
                <a href="#">
                    <img class="rounded-lg" src="{{$item['src']}}" alt="image description">
                </a>
                <figcaption class="absolute px-4 w-full text-lg text-white bottom-6">
                    <div class="flex justify-center items-center">
                        <p class="text-2xl text-yellow-600 font-bold">{{$item['name']}}</p>
                    </div>
                </figcaption>
            </figure>
        @endforeach
    </div>
</section>