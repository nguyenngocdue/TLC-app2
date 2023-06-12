@php 
$tmpl = $item->getChklst->getQaqcInspTmpl;
$prodOrder = $item->getChklst->getProdOrder;
$prodRouting = $prodOrder->getProdRouting;

if($prodOrder->production_name) {
    $a[] = "<span title='Production Name'>".$prodOrder->production_name."</span>";
}
if($prodOrder->compliance_name) {
    $a[] = "<span title='Compliance Name'>".$prodOrder->compliance_name."</span>";
}

$bigTitle = join(" / ", $a);
@endphp
<div class="px-4 flex justify-center ">
    <div class="p-4 w-full md:w-3/4 xl:w-1/2 dark:bg-gray-800 rounded-lg">
        <x-renderer.heading level=4 xalign="center">
            {!! $bigTitle !!}
        </x-renderer.heading>
        @if($project)
            <span title="Project #{{$project->id}}">{{$project->name}}</span>
            <i class="fa-solid fa-chevrons-right"></i>
            <span title="Sub-project #{{$subProject->id}}">{{$subProject->name}}</span>
            <i class="fa-solid fa-chevrons-right"></i>
            <span title="Checklist ID: #{{$chklst->id}}
Template: {{$tmpl->name}} (#{{$tmpl->id}})
ProdRouting: {{$prodRouting->name}} (#{{$prodRouting->id}})
ProdOrder: {{$prodOrder->name}} (#{{$prodOrder->id}})">{{$chklst->name}}</span>
        @endif
        <hr/>
        <x-renderer.heading level=5>
            <span title="Checklist Sheet #{{$item->id}} ({{$item->description}})">{{$item->name}}</span>
        </x-renderer.heading>

        @foreach($lines as $rowIndex => $line)
            <x-controls.insp-chklst.check-point :line="$line" table01Name="table01" :rowIndex="$rowIndex" />
        @endforeach

        <x-controls.insp-chklst.sign-off :signatures="$signatures" :type="$type" :item="$item"/>
        <input type="hidden" name="tableNames[table01]" value="qaqc_insp_chklst_lines">

        {{-- Those are for main body, not the table --}}
        <input type="hidden" name="name" value="{{$item->name}}">
        <input type="hidden" name="qaqc_insp_chklst_id" value="{{$item->qaqc_insp_chklst_id}}">
        {{-- status id is for change status submit button --}}
        <input type="hidden" name="status" id='status' value="{{$status}}"> 
        {{-- <input type="hidden" name="id" value="{{$item->id}}"> --}}
    </div>
</div>