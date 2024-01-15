@php
// $readOnly = false;
// $table01Name = 'table01'
@endphp

{{-- @dump($checkpoints) --}}

<input type="hidden" name="tableNames[{{$table01Name}}]" value="{{$lineType}}"/>
@foreach($groupedCheckpoints as $groupId => $group)
    <br/>
    <x-renderer.divider />
    <x-renderer.heading level=5 title="#{{$groupId}}">{{strtoupper($group['name'])}}</x-renderer.heading>
    @php
        $checkpoints = $group['items'];
        // dump($checkpoints);
    @endphp
    <div class="">
        @foreach($checkpoints as $rowIndex => $checkpoint)
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

