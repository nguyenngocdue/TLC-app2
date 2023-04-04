@if ($paginator->hasPages())
@php
$classBaseButton = "focus:shadow-outline-purple rounded border border-r-0 m-0.5 px-1 py-1 text-white transition-colors duration-150 focus:outline-none";
$className = "$classBaseButton border-purple-600 bg-purple-600";
$classNameDisabled = "$classBaseButton border-purple-200 bg-purple-200";
@endphp
<!-- Pagination -->
<ul class="inline-flex items-center">
    {{-- Previous Page Link --}}
    @if (!$paginator->onFirstPage())
    <li>
        <a href="{{ Str::removeFirstSlash($paginator->toArray()['first_page_url']) }}" class="{{$className}}" aria-label="Next">
            <span><i class="fas fa-angle-double-left"></i></span>
        </a>
    </li>
    <li>
        <a href="{{ Str::removeFirstSlash($paginator->previousPageUrl()) }}" class="{{$className}}" aria-label="Next">
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
                <li><a class="{{$className}}" href="{{Str::removeFirstSlash($url)}}">{{$page}}</a></li>
                @endif
            @endforeach
        @else
        .....
        @endif
    @endforeach
    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
    <li>
        <a class="{{$className}}" href="{{ Str::removeFirstSlash($paginator->nextPageUrl()) }}">
            <span><i class="fas fa-angle-right"></i></span>
        </a>
    </li>
    <li>
        <a class="{{$className}}" href="{{ Str::removeFirstSlash($paginator->toArray()['last_page_url']) }}">
            <span><i class="fas fa-angle-double-right"></i></span>
        </a>
    </li>
    @endif
</ul>
<!-- Pagination -->
{{-- <x-form.per-page type="{{$type}}" route="{{ route('updateUserSettings') }}" perPage="{{$perPage}}" /> --}}
@endif
