{{-- @dump($checkpoints) --}}
<hr/>
<div class="bg-white rounded border my-4">
    <x-controls.many-checkpoint-progressbar table01Name="{{$table01Name}}" :dataSource="$oriCheckPoints"/>
</div>
<hr/>

<input type="hidden" name="tableNames[{{$table01Name}}]" value="{{$lineType}}"/>
@php $index = 0; @endphp
@foreach($groupedCheckpoints as $groupId => $group)    
    @php
        $slug = Str::slug($group['name']);
    @endphp
    <x-renderer.heading id="{{$slug}}" title="#{{$groupId}}" level=5>{{$group['name']}}</x-renderer.heading>
    
    @foreach($group['items'] as $rowIndex => $checkpoint)
        <x-controls.insp-chklst.check-point 
        :line="$checkpoint" 
        :checkPointIds="$checkPointIds" 
        table01Name="{{$table01Name}}" 
        :rowIndex="$rowIndex" 
        type="{{$lineType}}"
        readOnly="{{$readOnly}}"
        />
    @endforeach
    @if($index++ < sizeof($groupedCheckpoints )-1)
    <x-renderer.divider></x-renderer.divider>
    @endif
@endforeach

<x-renderer.image-gallery-check-sheet :checkPointIds="$checkPointIds" :dataSource="$oriCheckPoints"/>