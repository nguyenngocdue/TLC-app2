@once
<script>
    const superProps = @json($superProps);
    const superWorkflows = @json($superWorkflows);
</script>
@endonce

@if($status && !($action === 'create'))
    <div class="w-full mb-4 p-2 bg-white rounded-lg dark:bg-gray-800 grid grid-cols-12 gap-2 no-print">
        <div class="col-span-12 md:col-span-4 lg:col-span-2">
            <x-renderer.card class="bg-white border py-1" title="Test Status" >
                <div class="mb-1">
                    @foreach($statuses as $key => $value)
                        @php
                            $colorIndex =$value['color_index'];  
                            $href = route($type.'.edit',$renderId)."?status=".$value['name']."&dryrun_token=".$dryRunToken;
                        @endphp
                        <x-renderer.status href="{!!$href!!}">{{$value['name']}}</x-renderer.status>
                    @endforeach
                </div>
            </x-renderer.card>
        </div>
        <div class="col-span-12 md:col-span-6 lg:col-span-8 w-full max-h-60">
            <x-renderer.card title="Accessible" class="bg-white border overflow-y-scroll " py="1">
                <div class="mb-1">
                    @isset($statuses[$status])
                        @foreach($statuses[$status]['capability-roles'] as $value)
                            <x-renderer.tag color="gray" rounded="rounded" class="ml-1">{{$value}}</x-renderer.tag>
                        @endforeach
                    @else
                        Status [{{$status}}] not found
                    @endisset
                </div>
            </x-renderer.card>
        </div>
        <div class="col-span-12 md:col-span-2 lg:col-span-2 w-full">
            <x-renderer.card class="bg-white border py-1" title="Quick Navigation">
                <div class="mb-1 text-center">
                    @if($previousItem)
                        <x-renderer.button title="Previous Record" href="{{route($type.'.edit',$previousItem->id)}}" icon="fa-duotone fa-backward" >#{{$previousItem->id}}</x-renderer.button>
                    @endif
                    @if($nextItem)
                        <x-renderer.button title="Next Record" href="{{route($type.'.edit',$nextItem->id)}}" icon="fa-duotone fa-forward  ">#{{$nextItem->id}}</x-renderer.button>
                    @endif
                </div> 
                Console.log
                <div class="mb-1 text-center">
                   <x-renderer.button size="xs" onClick="console.log(superProps)">Super-Props</x-renderer.button>
                   <x-renderer.button size="xs" onClick="console.log(superWorkflows)">Super-Workflows</x-renderer.button>
                </div>
            </x-renderer.card>
        </div>
        {{-- <div class="col-span-12 md:col-span-2 lg:col-span-2 w-full">
            <x-renderer.card title="Console.log()" class="bg-white border py-1">
               
            </x-renderer.card>
           
        </div> --}}
    </div>
@endif