@if(count($dataSource) > 0)
    @php $counter =0; @endphp
    <ol class="pl-5">
        @foreach($dataSource as $item)
        @php $counter ++; $counterStr = $counter.".";@endphp
        <li> {{$parentCounter.$counterStr}}
            {{-- <a href="{{ $item['href'] }}"> --}}
                {{ $item['name'] }}
            {{-- </a> --}}
            @if(!empty($item['children']))
            <x-navigation.table-of-content :dataSource="$item['children']" :parentCounter="$parentCounter.$counterStr" />
            @endif
        </li>
        @endforeach
    </ol>
@endif