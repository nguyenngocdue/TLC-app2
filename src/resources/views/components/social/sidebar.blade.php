<section class="side-bar bg-white dark:bg-[#18191a] overflow-scroll border-r scrollbar-hide hidden xl:flex flex-col top-0 {{$right ? 'right-0' : 'left-0'}} pt-16 
        fixed hover:scrollbar-thumb-slate-400 hover:scrollbar-default mb-5">
        <div class="px-4">
            {{$slot}}
        </div>
</section>