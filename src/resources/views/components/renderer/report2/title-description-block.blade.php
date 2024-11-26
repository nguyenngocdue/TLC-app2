@php
    $isAdmin = App\Utils\Support\CurrentUser::isAdmin();
@endphp
{{-- @if ($isAdmin && !$block->title && !$block->description) --}}
@if ($isAdmin)
<div class="no-print" style="bottom : -15%">
        <span class="inline-flex items-center rounded-md bg-purple-50 px-2   py-1 text-xs font-medium text-purple-700 ring-1 ring-inset ring-purple-700/10">
            <a title='Edit Block' class="p-2 flex justify-start" href="{{ route('rp_blocks.edit', $block->id) }}" target="blank" >
                <i class="fa-solid fa-gear"></i>
            </a>
        </span>
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
