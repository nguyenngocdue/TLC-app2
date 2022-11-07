@props([
'title' => 'Untitled Card',
'description'=>'Lorem ipsum dolor sit, amet consectetur adipisicing elit.',
'items' => [],
])

<div class="min-w-0 p-4 bg-white border rounded-lg shadow-xs dark:bg-gray-800">
    <h4 class="mb-4 font-semibold text-gray-600 dark:text-gray-300">
        {{$title}}
    </h4>
    <p class="text-gray-600 dark:text-gray-400">
        {{$description}}
    </p>
    @foreach($items as $item)
    {{ $item }}
    @endforeach
</div>