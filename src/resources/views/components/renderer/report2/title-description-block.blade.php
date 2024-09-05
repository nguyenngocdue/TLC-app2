@if($block->title)
<x-renderer.heading level=1 title="Name: {{ $block->name }}" class="font-bold text-center pt-2">
    <a class="items-center" href="{{ route('rp_blocks.edit', $block->id) }}" target="blank" >
        {{ $block->title }}
    </a>
</x-renderer.heading>
@endif
@if( $block->description)
    <x-renderer.heading level=3 class="font-medium text-center pb-1">{{ $block->description }}</x-renderer.heading>
@endif
