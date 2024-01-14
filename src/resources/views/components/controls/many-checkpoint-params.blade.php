@php
$readOnly = false;
@endphp

{{-- @dump($checkpoints) --}}

@foreach($groupedCheckpoints as $groupId => $group)
    <br/>
    <x-renderer.heading level=5 title="#{{$groupId}}">{{strtoupper($group['name'])}}</x-renderer.heading>
    @php
        $checkpoints = $group['items'];
        // dump($checkpoints);
    @endphp
    <div class="ml-20">
        @foreach($checkpoints as $rowIndex => $checkpoint)
        <x-controls.insp-chklst.check-point 
            :line="$checkpoint" 
            :checkPointIds="$checkPointIds" 
            table01Name="table01" 
            :rowIndex="$rowIndex" 
            type="{{$lineType}}"
            readOnly="{{$readOnly}}"
        />
        @endforeach
    </div>
@endforeach

