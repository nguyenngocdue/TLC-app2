@php 
$tmpl = $item->getChklst->getQaqcInspTmpl;
$prodOrder = $item->getChklst->getProdOrder;
$prodRouting = $prodOrder->getProdRouting;

if($prodOrder->production_name) $a[] = "<span title='Production Name'>".$prodOrder->production_name."</span>";
if($prodOrder->compliance_name) $a[] = "<span title='Compliance Name'>".$prodOrder->compliance_name."</span>";

$bigTitle = join(" / ", $a);
if($project) $href = route("qaqc_insp_chklsts.edit", $chklst->id);
@endphp
<x-renderer.heading level=4 class="text-center">
    {!! $bigTitle !!}
</x-renderer.heading>
@if($project)
    <span title="Project #{{$project->id}}">{{$project->name}}</span>
    <i class="fa-solid fa-chevrons-right"></i>
    <span title="Sub-project #{{$subProject->id}}">{{$subProject->name}}</span>
    <i class="fa-solid fa-chevrons-right"></i>
    <a href="{{$href}}" class="text-blue-500" title="Checklist ID: #{{$chklst->id}}
Template: {{$tmpl->name}} (#{{$tmpl->id}})
ProdRouting: {{$prodRouting->name}} (#{{$prodRouting->id}})
ProdOrder: {{$prodOrder->name}} (#{{$prodOrder->id}})">{{$chklst->name}}</a>
@endif