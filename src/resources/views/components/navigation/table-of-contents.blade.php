@if(is_iterable($dataSource) && count($dataSource) > 0)
{{-- @dump($dataSource) --}}
    @php $counter =0; @endphp
    <ol class="pl-5">
        @foreach($dataSource as $item)
        @php $counter ++; $counterStr = $counter.".";@endphp
        <li class="hover:bg-blue-500 p-1 rounded"> {{$parentCounter.$counterStr}}
            <a href="#exam_group_{{ $item['id'] }}">
                {{ $item['name'] }}
            </a>
            @if(!empty($item['children']))
            <x-navigation.table-of-contents :dataSource="$item['children']" :parentCounter="$parentCounter.$counterStr" />
            @endif
        </li>
        @endforeach
    </ol>
@endif