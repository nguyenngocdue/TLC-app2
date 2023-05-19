@foreach($dataSource as $key => $items)
@php
$name = App\Utils\Support\Report::replaceAndUcwords($key);
@endphp
<x-renderer.card title={{$name}}>
    <div class="grid lg:grid-cols-5 md:grid-cols-5 grid-cols-2 gap-4">
        @foreach($items as $status => $value)
        <div class="flex items-center">
            <div class="border dark:border-gray-600 h-8 w-8 {{$value->legend_color}} items-center p-1 mr-2 border-r text-center">
                {!!$value->legend_icon ?? ''!!}
            </div> {{$value->title}}
        </div>
        @endforeach
    </div>
</x-renderer.card>
@endforeach
