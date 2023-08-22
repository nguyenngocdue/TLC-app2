<div component="ViewAllTypeSelector" class="w-full text-right mr-2">
    @forelse($tabs as $key => $value)
    <x-renderer.button type="secondary" outline="{{$value['active']?false:true}}" href="{!!$value['href']!!}" title="{{$value['title']}}">
        <i class="{{$value['icon']}}"></i> {{$value['title']}}
    </x-renderer.button>
    @empty

    @endforelse
</div>