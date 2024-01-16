@php
// $readOnly = false;
// $table01Name = 'table01'
@endphp

{{-- @dump($checkpoints) --}}

<input type="hidden" name="tableNames[{{$table01Name}}]" value="{{$lineType}}"/>
@foreach($groupedCheckpoints as $groupId => $group)    
    @php
        $slug = Str::slug($group['name']);
    @endphp
    <x-renderer.divider>
        <x-renderer.heading id="{{$slug}}" title="#{{$groupId}}" level=5>{{$group['name']}}</x-renderer.heading>
    </x-renderer.divider>

    <div class="">
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
    </div>
@endforeach

<x-renderer.image-gallery-check-sheet :checkPointIds="$checkPointIds" :dataSource="$oriCheckPoints"/>