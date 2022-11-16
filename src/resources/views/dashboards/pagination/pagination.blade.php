@if ($paginator->hasPages())
<!-- Pagination -->
<ul class="inline-flex items-center">
    {{-- Previous Page Link --}}
    @if (!$paginator->onFirstPage())
    <li>
        <a href="{{ $paginator->toArray()['first_page_url'] }}" class="focus:shadow-outline-purple rounded-md border border-r-0 border-purple-600 bg-purple-600 m-0.5 px-3 py-1 text-white transition-colors duration-150 focus:outline-none" aria-label="Next">
            <span><i class="fas fa-angle-double-left"></i></span>
        </a>
    </li>
    <li>
        <a href="{{ $paginator->previousPageUrl() }}" class="focus:shadow-outline-purple rounded-md border border-r-0 border-purple-600 bg-purple-600 m-0.5 px-3 py-1 text-white transition-colors duration-150 focus:outline-none" aria-label="Next">
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
    <li><span class="focus:shadow-outline-purple rounded-md border border-r-0 border-purple-600 bg-purple-600 m-0.5 px-3 py-1 text-white transition-colors duration-150 focus:outline-none">{{ $page }}</span></li>
    @elseif ($page == $paginator->currentPage() + 1 ||$page == $paginator->lastPage() - 1 ||$page == $paginator->lastPage())
    <li><a class="focus:shadow-outline-purple rounded-md px-3 py-1 focus:outline-none" href="{{ $url }}">{{ $page }}</a></li>
    @elseif ($page == $paginator->currentPage() + 2 || $page == $paginator->lastPage() - 2)
    <li class="disabled"><span class="focus:shadow-outline-purple rounded-md px-3 py-1 focus:outline-none"><i class="fa fa-ellipsis-h"></i></span></li>
    @endif


    @endforeach
    @endif
    @endforeach

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
    <li>
        <a class="focus:shadow-outline-purple rounded-md border border-r-0 border-purple-600 bg-purple-600 m-0.5 px-3 py-1 text-white transition-colors duration-150 focus:outline-none" href="{{ $paginator->nextPageUrl() }}">
            <span><i class="fas fa-angle-right"></i></span>
        </a>
    </li>
    <li>
        <a class="focus:shadow-outline-purple rounded-md border border-r-0 border-purple-600 bg-purple-600 m-0.5 px-3 py-1 text-white transition-colors duration-150 focus:outline-none" href="{{ $paginator->toArray()['last_page_url'] }}">
            <span><i class="fas fa-angle-double-right"></i></span>
        </a>
    </li>
    @endif
</ul>
<!-- Pagination -->
@endif