<x-renderer.card title="{{$title}}">
    <div class="grid grid-cols-{{$span}}">
    @foreach($dataSource as $values)
        <ul class="max-w-md space-y-1 text-gray-500 list-inside dark:text-gray-400">
            @foreach($values as $value)
            <li class="flex items-center border-b">
                <i class="fa-solid fa-empty-set px-4"></i>{{$value}}
            </li>
            @endforeach
        </ul>
    @endforeach
    </div>
</x-renderer.card>
