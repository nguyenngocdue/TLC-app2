@if ($paginator->hasPages())
    <!-- Pagination -->   
        <ul class="inline-flex items-center">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li >
                    <span class="px-3 py-1 rounded-md rounded-l-lg focus:outline-none focus:shadow-outline-purple">
                        <i class="fas fa-angle-double-left"></i>
                    </span>
                </li>
                <li class="disabled">
                    <span class="px-3 py-1 rounded-md rounded-l-lg focus:outline-none focus:shadow-outline-purple">
                        <i class="fas fa-angle-left"></i>
                    </span>
                </li>                
            @else
                <li>
                    <a  href="{{ $paginator->toArray()['first_page_url'] }}"
                        class="px-3 py-1 text-white transition-colors duration-150 bg-purple-600 border border-r-0 border-purple-600 rounded-md focus:outline-none focus:shadow-outline-purple"
                        aria-label="Next">
                        <span><i class="fas fa-angle-double-left"></i></span>
                    </a>
                </li>
                <li>
                    <a  href="{{ $paginator->previousPageUrl() }}"
                        class="px-3 py-1 text-white transition-colors duration-150 bg-purple-600 border border-r-0 border-purple-600 rounded-md focus:outline-none focus:shadow-outline-purple"
                        aria-label="Next">
                        <span><i class="fas fa-angle-left"></i></span>
                    </a>
                </li>
            @endif
            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li>
                                <span 
                                    class="px-3 py-1 text-white transition-colors duration-150 bg-purple-600 border border-r-0 border-purple-600 rounded-md focus:outline-none focus:shadow-outline-purple" >{{ $page }}
                                </span>
                            </li>
                        @elseif ($page == $paginator->currentPage() + 1  || $page == $paginator->lastPage() -1 || $page == $paginator->lastPage())
                            <li><a 
                                class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple" 
                                href="{{ $url }}">{{ $page }}</a></li>
                        @elseif ($page == $paginator->currentPage() + 2 || $page == $paginator->lastPage()-2)
                            <li class="disabled"><span 
                                class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple"
                                ><i class="fa fa-ellipsis-h"></i></span></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li >
                    <a class="px-3 py-1 text-white transition-colors duration-150 bg-purple-600 border border-r-0 border-purple-600 rounded-md focus:outline-none focus:shadow-outline-purple" 
                    href="{{ $paginator->nextPageUrl() }}">
                        <span><i class="fas fa-angle-right"></i></span>
                    </a>
                </li>
                <li >
                    <a class="px-3 py-1 text-white transition-colors duration-150 bg-purple-600 border border-r-0 border-purple-600 rounded-md focus:outline-none focus:shadow-outline-purple" 
                    href="{{ $paginator->toArray()['last_page_url'] }}">
                        <span><i class="fas fa-angle-double-right"></i></span>
                    </a>
                </li>
            @else
                <li >
                    <span class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple">
                        <i class="fas fa-angle-right"></i>
                    </span>
                </li>
                <li >
                    <span class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple">
                        <i class="fas fa-angle-double-right"></i>
                    </span>
                </li>
            @endif
        </ul> 
    <!-- Pagination -->
@endif