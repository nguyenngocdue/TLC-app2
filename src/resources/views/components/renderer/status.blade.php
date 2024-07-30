@if($href) <a href='{{$href}}'> @endif
<x-renderer.tag color='{{$color}}' colorIndex='{{$colorIndex}}' title='{!! $tooltip !!}' class='{{$class}}'>
    {{ $title }}
</x-renderer.tag>
@if($href) </a> @endif