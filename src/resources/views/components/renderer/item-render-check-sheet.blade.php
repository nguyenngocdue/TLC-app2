<x-renderer.heading level=5>
    <span title="Checklist Sheet #{{$item->id}}">{{$item->description}}</span>
</x-renderer.heading>
<span title="Project #{{$project->id}}">{{$project->name}}</span>
<i class="fa-solid fa-chevrons-right"></i>
<span title="Sub-project #{{$subProject->id}}">{{$subProject->name}}</span>
<i class="fa-solid fa-chevrons-right"></i>
<span title="Checklist #{{$chklst->id}}">{{$chklst->name}}</span>

@foreach($lines as $line)
    <x-controls.check-point :line="$line" />
@endforeach