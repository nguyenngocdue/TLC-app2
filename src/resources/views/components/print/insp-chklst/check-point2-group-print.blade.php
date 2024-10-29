@php $cpIndex = 0; @endphp
@foreach($groupedCheckpoints as $group)
<br/>
<h2 class="uppercase font-bold w-full text-lg-vw text-gray-600 bg-gray-200 p-2 border border-gray-600" 
    id="{{$group['groupId']}}"
    >{{ $group['groupName'] }}</h2>

    @foreach($group['checkpoints'] as $checkpoint)
    <div class="grid grid-cols-12 text-sm">
        <div class="col-span-1 border border-gray-500 p-2 flex justify-center text-md-vw items-center">
            {{++$cpIndex}}
        </div>
        <div class="col-span-4 border border-gray-500 p-2 flex items-center text-md-vw">                    
            {{ $checkpoint->name }}
        </div>
        <div class="col-span-7 border border-gray-500 p-2">
            <x-print.insp-chklst.check-point2-print :checkpoint="$checkpoint" />
        </div>
    </div>
    @endforeach
@endforeach