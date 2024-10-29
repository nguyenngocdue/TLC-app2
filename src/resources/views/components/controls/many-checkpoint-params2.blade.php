{{-- @dump($groupedCheckpoints) --}}
{{-- @dump($readOnly) --}}
{{-- @dump($item) --}}
<hr/>
<div class="bg-white rounded border my-4">
    <x-controls.many-checkpoint-progressbar2
        categoryName="{{$categoryName}}"
        table01Name="{{$table01Name}}" 
        :dataSource="$checkPointRadioIds"
        :progressData="$progressData"
        />
</div>
<hr/>
<input type="hidden" name="tableNames[{{$table01Name}}]" value="{{$lineType}}"/>
@php $groupIndex = 0; $cpIndex=0; @endphp
@foreach($groupedCheckpoints as $groupId => $group)        
    <x-renderer.heading id="{{Str::slug($group['groupName'])}}" title="#{{$groupId}}" level=5 class="font-bold uppercase p-2">
        {{$group['groupName']}}
    </x-renderer.heading>
   
    @foreach($group['checkpoints'] as $checkpoint)    
        <x-controls.insp-chklst.check-point2
            type="{{$lineType}}"
            readOnly="{{$readOnly}}"
            index="{{++$cpIndex}}"
            table01Name="{{$table01Name}}"
            rowIndex="{{$checkpoint->id}}"
            categoryName="{{$categoryName}}"
            
            :line="$checkpoint" 
            :checkPointIds="$checkPointIds"
            />
    @endforeach
  
    @if($groupIndex++ < sizeof($groupedCheckpoints )-1)
    <x-renderer.divider></x-renderer.divider>
    @endif

@endforeach

<x-renderer.image-gallery-check-sheet :checkPointIds="$checkPointIds" :dataSource="$oriCheckPoints"/>