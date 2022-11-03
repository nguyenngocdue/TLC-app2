<ol class="flex text-[#00000073] ">
    <li><span>
            <a class="text-blue-500 hover:text-gray-400" href="{{ route($type . '_viewall.index') }}">View All</a>
        </span>
        <span class="px-2"> / </span>
    </li>
    <li>
        <span>
            <a class="text-blue-500 hover:text-gray-400" href="{{ route($type . '_addnew.create') }}"> Add New</a>
        </span>
        <span class="px-2"> / </span>
    </li>
    <li>
        <span>
            <a class="text-blue-500 hover:text-gray-400" href="{{ route(Str::singular($type) . '_mngprop.index')}}"> Props</a>
        </span>
        <span class="px-2"> / </span>
    </li>
    <li>
        <span class="">
            <a class="text-blue-500 hover:text-gray-400" href="{{ route(Str::singular($type) .'_mngrls.index') }}"> Relationships</a>
        </span>
    </li>
</ol>
