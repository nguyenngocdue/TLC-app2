@if ($paginator->hasPages())
    <!-- Pagination -->    
        <ul class="pagination justify-content-center">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link"><i class="fas fa-angle-double-left"></i></span>
                </li>
                <li class="page-item disabled">
                    <span class="page-link"><i class="fas fa-angle-left"></i></span>
                </li>                
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->toArray()['first_page_url'] }}">
                        <span><i class="fas fa-angle-double-left"></i></span>
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}">
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
                            <li class="page-item"><span class="page-link" >{{ $page }}</span></li>
                        @elseif ($page == $paginator->currentPage() + 1  || $page == $paginator->lastPage() -1 || $page == $paginator->lastPage())
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @elseif ($page == $paginator->currentPage() + 2 || $page == $paginator->lastPage()-2)
                            <li class="page-item disabled"><span class="page-link"><i class="fa fa-ellipsis-h"></i></span></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}">
                        <span><i class="fas fa-angle-right"></i></span>
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->toArray()['last_page_url'] }}">
                        <span><i class="fas fa-angle-double-right"></i></span>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link"><i class="fas fa-angle-right"></i></span>
                </li>
                <li class="page-item disabled">
                    <span class="page-link"><i class="fas fa-angle-double-right"></i></span>
                </li>
            @endif
        </ul>    
    <!-- Pagination -->
@endif