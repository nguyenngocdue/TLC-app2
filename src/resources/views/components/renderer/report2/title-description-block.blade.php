@php
    $isAdmin = App\Utils\Support\CurrentUser::isAdmin();
@endphp
{{-- @if ($isAdmin && !$block->title && !$block->description) --}}
@if ($isAdmin)
<div class="no-print relative" >
    <a title='Edit Block' style="top : -10px ; left : -25px;" class="p-2 bg-transparent flex justify-start absolute" href="{{ route('rp_blocks.edit', $block->id) }}" target="blank" >
        <i class=" text-purple-500 fa-solid fa-gear text-sm "></i>
    </a>
</div>
@endif

@if($block->title)
    <x-renderer.heading level=3 title="Name: {{ $block->name }}" class="{{-- font-bold  --}} text-center p-2">
        <a class="items-center" href="{{ route('rp_blocks.edit', $block->id) }}" target="blank" >
            {{ $block->title }}
        </a>
    </x-renderer.heading>
@endif
@if( $block->description)
    <x-renderer.heading level=4 class="{{-- font-bold  --}} text-center {{ $block->title  ? 'py-2' : 'py-4'}}">
        <a class="items-center" href="{{ route('rp_blocks.edit', $block->id) }}" target="blank" >
            {{ $block->description }}
        </a>
    </x-renderer.heading>
@endif
