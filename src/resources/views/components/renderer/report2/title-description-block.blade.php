<x-renderer.heading level=5 title="Name: {{ $block->name }}">
    <a href="{{ route('rp_blocks.edit', $block->id) }}" target="blank">
        {{ $block->title }}
        <x-renderer.heading level=6>{{ $block->description }}</x-renderer.heading>
    </a>
</x-renderer.heading>
