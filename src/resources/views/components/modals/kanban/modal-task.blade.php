@props(['groupWidth'])

{{-- @extends("modals.modal-large") --}}
@extends("modals.modal-medium")
{{-- @extends("modals.modal-small") --}}

@section($modalId.'-header', "Edit Task")

@section($modalId.'-body')
    <input id="txtTaskId" type="hidden" x-bind:value="modalParams['{{$modalId}}']['id']">
    <div id="divTaskBody" class="my-4">
        <x-renderer.loading/>
    </div>
@endsection

@section($modalId.'-footer')
    <x-modals.kanban.modal-footer 
        modalId="{{$modalId}}" 
        groupWidth="{{$groupWidth}}"
        txtTypeId="txtTaskId" 
        route="route_task" 
        prefix="task_parent_"
        />
@endsection

@section($modalId.'-javascript')
<script>
    setTimeout(() => kanbanLoadModalRenderer('txtTaskId', 'divTaskBody', route_task), 100);
</script>
@endsection