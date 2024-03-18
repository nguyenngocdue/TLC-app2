@if(sizeof($tabs) > 0)
    <div component="ViewAllTypeSelector" class="w-full text-right rounded p-2 mr-2 bg-gray-100">
        @forelse($tabs as $key => $value)
        <x-renderer.button type="secondary" outline="{{$value['active']?false:true}}" href="{!!$value['href']!!}" title="{{$value['title']}}">
            <i class="{{$value['icon']}}"></i> {{$value['title']}}
        </x-renderer.button>
        @empty

        @endforelse
    </div>
@endif