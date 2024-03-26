<section id="faqs" class="p-12">
    
<h1 class="font-bold text-4xl text-center my-5">Frequent Asked Questions</h1>
<ul class="max-w-5xl mx-auto w-full md:mx-auto space-y-2">
    @foreach($dataSource as $faq)
    <li class="border 1rounded-lg">
        <details class="group">
            <summary class="flex 1rounded-t-lg bg-blue-300 items-center justify-between gap-3 px-4 py-3 font-medium marker:content-none hover:cursor-pointer">
                <span class="text-lg ">{{$faq['title']}}</span>
                <svg class="w-5 h-5 text-gr1ay-500 transition group-open:rotate-90" xmlns="http://www.w3.org/2000/svg"
                    width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z">
                    </path>
                </svg>
            </summary>
            <article class="px-8 py-4 bg-white">
                <p>
                    {{$faq['content']}}
                </p>
            </article>
        </details>
    </li>
    @endforeach
</ul>
</section>