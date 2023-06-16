@forelse($tabs as $key => $value)
@php
    $isActive = $value['active'];
    $render = $isActive ? 'type="secondary"' : 'type="secondary" outline=true';
@endphp
<x-renderer.button class="mr-1" href="{!!$value['href']!!}" title="{{$value['title']}}">
    <i class="{{$value['icon']}}"></i>
</x-renderer.button>
@empty

@endforelse