<div class="flex justify-end">
    @foreach($links as $value)
        @if (isset($value['href']))
        <div class="{{$classList}}">
            <a class="text-lg text-blue-500 hover:text-gray-400" target="{{isset($value['target'])?$value['target']:''}}" href="{{$value['href']}}">
                {!!$value['icon']!!}
                <span class="hidden sm:flex text-xs font-normal">{!! $value['title'] !!}</span>
            </a>
        </div>
        @elseif(isset($value['type']))
            @switch($value['type'])
                @case('modePrint')
                    <div class="{{$classList}}">
                        <button class="text-lg text-blue-500 hover:text-gray-400" onclick="window.print();">
                            {!!$value['icon']!!}
                            <span class="hidden sm:flex text-xs font-normal">{!! $value['title'] !!}</span>
                        </button>
                    </div>
                @break
                @case('modal')
                    <div class="{{$classList}}">
                        <button class="text-lg text-blue-500 hover:text-gray-400 focus:outline-none" 
                            @click="toggleModal('{{$value['modalId']}}')"
                            @keydown.escape="closeModal('{{$value['modalId']}}')"
                            >
                            {!!$value['icon']!!}
                            <span class="hidden sm:flex text-xs font-normal">{!! $value['title'] !!}</span>
                        </button>
                    </div>
                    {!! $value['modalBody'] !!}
                @break
                @case('selectDropdown')
                    <div class="relative {{$classList}}">
                        <button class="text-lg flex items-center sm:block text-blue-500 hover:text-gray-400" 
                            data-dropdown-toggle="{{$value['id']}}" 
                            data-dropdown-delay="500" 
                            data-dropdown-trigger="click" 
                            >
                            {!!$value['icon']!!}
                            <span class="flex sm:hidden"><i class="fa-solid text-sm fa-chevron-down pl-1"></i></span>
                            <span class="hidden sm:flex text-xs font-normal">
                                <span>{!! $value['title'] !!}</span>
                                <i class="fa-solid fa-chevron-down pl-1"></i>
                            </span>
                        </button>
                        <x-renderer.select-dropdown id="{{$value['id']}}" :dataSource="$value['dataSource']" />
                        @if(isset($value['badge']))
                            <x-renderer.badge>{{$value['badge']}}</x-renderer.badge>                           
                        @endif
                    </div>
                @break
                @default
                    Unknown type {{$value['type'] }} 
                @break
            @endswitch
        @endif        
    @endforeach
</div>
  