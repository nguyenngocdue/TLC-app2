@if (isset($dataSource) && sizeof($dataSource) > 3)
    <ul class="list-disc list-inside  grid grid-cols-12 p-1">
        @foreach($dataSource as $value)
            <li class="col-start-{{24/$colSpan+1}} col-span-{{12 - 24/$colSpan}}">
                {{$value}}
            </li>
        @endforeach
    </ul>
@else
    @php
        $content = implode(', ',$dataSource);
    @endphp
    <span>{{$content}}</span>
@endif