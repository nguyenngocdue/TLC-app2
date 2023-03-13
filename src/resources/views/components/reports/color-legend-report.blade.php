@foreach($dataSource as $key => $values)
@php
    $name = App\Utils\Support\Report::replaceAndUcwords($key);
@endphp
<x-renderer.card title={{$name}}>
    <div class="grid lg:grid-cols-5 md:grid-cols-5 grid-cols-2">
        @foreach($values as $color => $title)
            <div class="flex"><div class="border h-6 w-6 mr-2 {{$color}}"></div>{{$title}}</div>
        @endforeach
    </div>
</x-renderer.card>
@endforeach