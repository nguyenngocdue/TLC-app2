@php
        $progressData = [
            [
                'color' => 'green',
                'percent' => '10%',
                'label' => 'Yes',
            ],
            [
                'color' => 'pink',
                'percent' => '70%',
                'label' => 'No',
            ],
            [
                'color' => 'gray',
                'percent' => '10%',
                'label' => 'N/A',
            ],
            [
                'color' => 'orange',
                'percent' => '10%',
                'label' => 'On Hold',
            ],
        ];
    @endphp
    <div class="flex justify-center "> 
        <div class="{{$formWidth}}"> 
            <x-renderer.heading level="4" xalign="center" title="#{{$item->id}}">
                {{strtoupper( $item->name)}}
            </x-renderer.heading>
        
            <div class=" border bg-white rounded p-5">
                <div class="flex justify-between">
                    <div class="grid grid-cols-12">
                        <div class="col-span-4">Project:</div><div class="col-span-8"> {{$item->getChklst->getSubProject->name}}</div>
                        <div class="col-span-4">Module:</div><div class="col-span-8"> {{$item->getChklst->getProdOrder->production_name}}</div>
                    </div>
                    <div class="mx-4">
                        <img class="w-40" src="{{asset('logo/tlc.png')}}" />
                    </div>
                </div>
            </div>
            <div class="bg-white rounded border">
                <x-renderer.progress-bar :dataSource="$progressData"/>
            </div>
        </div>
    </div>