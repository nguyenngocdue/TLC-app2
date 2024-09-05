@php
    $isAdmin = App\Utils\Support\CurrentUser::isAdmin();

@endphp
    @if ($isAdmin && !$block->title && !$block->description)
    <a class="block" href="{{ route('rp_blocks.edit', $block->id) }}" target="blank" >
        <i class="text-center fa-solid fa-gear "></i>
    </a>
    @endif

@if($block->title)
<x-renderer.heading level=1 title="Name: {{ $block->name }}" class="font-bold text-center pt-2">
    <a class="items-center" href="{{ route('rp_blocks.edit', $block->id) }}" target="blank" >
        {{ $block->title }}
    </a>
</x-renderer.heading>
@endif
@if( $block->description)
    <x-renderer.heading level=3 class="font-medium text-center pb-1">
        <a class="items-center" href="{{ route('rp_blocks.edit', $block->id) }}" target="blank" >
            {{ $block->description }}
        </a>
    </x-renderer.heading>
@endif
