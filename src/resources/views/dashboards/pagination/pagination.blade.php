@if ($paginator->hasPages())
@php
$className = "focus:shadow-outline-purple rounded-md border border-r-0 border-purple-600 bg-purple-600 m-0.5 px-3 py-1 text-white transition-colors duration-150 focus:outline-none";
$classNameDisabled = "focus:shadow-outline-purple rounded-md border border-r-0 border-purple-100 bg-purple-100 m-0.5 px-3 py-1 text-white transition-colors duration-150 focus:outline-none";
@endphp
<span class="hidden focus:shadow-outline-purple rounded-md border border-r-0 border-purple-600 bg-purple-600 m-0.5 px-3 py-1 text-white transition-colors duration-150 focus:outline-none">Active</span>
<span class="hidden focus:shadow-outline-purple rounded-md border border-r-0 border-purple-100 bg-purple-100 m-0.5 px-3 py-1 text-white transition-colors duration-150 focus:outline-none">Disabled</span>
<!-- Pagination -->
<ul class="inline-flex items-center">
    {{-- Previous Page Link --}}
    @if (!$paginator->onFirstPage())
    <li>
        <a href="{{ substr($paginator->toArray()['first_page_url'],1) }}" class="{{$className}}" aria-label="Next">
            <span><i class="fas fa-angle-double-left"></i></span>
        </a>
    </li>
    <li>
        <a href="{{ substr($paginator->previousPageUrl(),1) }}" class="{{$className}}" aria-label="Next">
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
    <li><a class="{{$classNameDisabled}}">{{$page}}</a></li>
    @else
    <li><a class="{{$className}}" href="{{substr($url,1)}}">{{$page}}</a></li>
    @endif
    {{-- @if ($page == $paginator->currentPage())
    <li><span class="focus:shadow-outline-purple rounded-md border border-r-0 border-purple-600 bg-purple-600 m-0.5 px-3 py-1 text-white transition-colors duration-150 focus:outline-none">{{ $page }}</span></li>
    @elseif ($page == $paginator->currentPage() + 1 ||$page == $paginator->lastPage() - 1 ||$page == $paginator->lastPage())
    <li><a class="focus:shadow-outline-purple rounded-md px-3 py-1 focus:outline-none" href="{{ $url }}">{{ $page }}</a></li>
    @elseif ($page == $paginator->currentPage() + 2 || $page == $paginator->lastPage() - 2)
    <li class="disabled"><span class="focus:shadow-outline-purple rounded-md px-3 py-1 focus:outline-none"><i class="fa fa-ellipsis-h"></i></span></li>
    @endif --}}


    @endforeach
    @else
    .....
    @endif

    @endforeach

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
    <li>
        <a class="{{$className}}" href="{{ substr($paginator->nextPageUrl(),1) }}">
            <span><i class="fas fa-angle-right"></i></span>
        </a>
    </li>
    <li>
        <a class="{{$className}}" href="{{ substr($paginator->toArray()['last_page_url'],1) }}">
            <span><i class="fas fa-angle-double-right"></i></span>
        </a>
    </li>
    @endif
</ul>
<!-- Pagination -->
@endif
