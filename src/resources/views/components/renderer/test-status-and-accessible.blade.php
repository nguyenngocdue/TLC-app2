@if($isAdmin)
        @if($status && !($action === 'create'))
            <div class="w-full mb-8 p-2 bg-white rounded-lg  dark:bg-gray-800">
                <x-renderer.card title="Test Status" >
                    <div class="mb-3">
                        @foreach($statuses as $key => $value)
                            <span class="bg-{{$value['color']}}-{{$value['color_index']}} whitespace-nowrap rounded hover:bg-blue-400 font-medium text-xs px-2 py-1.5 leading-tight mx-1">
                                <a href="{{route($type.'.edit',$renderId)}}?status={{$value['name']}}">{{$value['title']}}</a>
                            </span>
                        @endforeach
                    </div>
                </x-renderer.card>
                <x-renderer.card title="Accessible" >
                    <div class="mb-3">
                        @foreach($statuses[$status]['capability-roles'] as $value)
                            <span><x-renderer.tag color="gray" rounded="rounded" class="ml-1">{{$value}}</x-renderer.tag></span>
                        @endforeach
                    </div>
                </x-renderer.card>
            </div>
        @endif
    @endif