@once
<script>
    const superProps = @json($superProps);
    const superWorkflows = @json($superWorkflows);
</script>
@endonce

@if($status && !($action === 'create'))
    <div class="w-full mb-4 p-2 bg-white rounded-lg dark:bg-gray-800 grid grid-cols-12 gap-2 no-print">
        <div class="col-span-12 md:col-span-5 lg:col-span-3">
            <x-renderer.card title="Test Status" py="1" >
                <div class="mb-1">
                    @foreach($statuses as $key => $value)
                        @php $colorIndex =$value['color_index'];  @endphp
                        <span class="bg-{{$value['color']}}-{{$colorIndex}} text-{{$value['color']}}-{{1000-$colorIndex}} whitespace-nowrap rounded hover:bg-blue-400 font-medium text-xs px-2 py-1 leading-tight ml-1">
                            <a href="{{route($type.'.edit',$renderId)}}?status={{$value['name']}}&dryrun_token={{$dryRunToken}}">{{$value['title']}}</a>
                        </span>
                    @endforeach
                </div>
            </x-renderer.card>
        </div>
        <div class="col-span-12 md:col-span-5 lg:col-span-7 w-full">
            <x-renderer.card title="Accessible" py="1">
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
            <x-renderer.card title="Console.log()" py="1">
                <div class="mb-1 text-center">
                   <x-renderer.button size="xs" onClick="console.log(superProps)">Super-Props</x-renderer.button>
                   <x-renderer.button size="xs" onClick="console.log(superWorkflows)">Super-Workflows</x-renderer.button>
                </div>
            </x-renderer.card>
        </div>
    </div>
@endif