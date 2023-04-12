<div class="col-span-12 flex justify-center">
    <div class="w-1/2">
        <x-renderer.heading level=5>
            <span title="Checklist Sheet #{{$item->id}}">{{$item->description}}</span>
        </x-renderer.heading>
        <span title="Project #{{$project->id}}">{{$project->name}}</span>
        <i class="fa-solid fa-chevrons-right"></i>
        <span title="Sub-project #{{$subProject->id}}">{{$subProject->name}}</span>
        <i class="fa-solid fa-chevrons-right"></i>
        <span title="Checklist #{{$chklst->id}}">{{$chklst->name}}</span>

        @foreach($lines as $rowIndex => $line)
            <x-controls.check-point :line="$line" table01Name="table01" :rowIndex="$rowIndex" />
        @endforeach
    </div>
</div>
<input type="hidden" name="tableNames[]" value="qaqc_insp_chklst_lines">

{{-- Those are for main body, not the table --}}
<input type="hidden" name="name" value="{{$item->name}}">
<input type="hidden" name="qaqc_insp_chklst_id" value="{{$item->qaqc_insp_chklst_id}}">