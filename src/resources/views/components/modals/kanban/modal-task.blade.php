
{{-- @extends("modals.modal-large") --}}
@extends("modals.modal-medium")
{{-- @extends("modals.modal-small") --}}

@section($modalId.'-header', "Edit Task")

@section($modalId.'-body')
    <input id="txtTaskId" type="hidden" x-bind:value="modalParams['{{$modalId}}']['id']">
    <div id="divTaskBody" class="my-4"></div>
@endsection

@section($modalId.'-footer')
    <x-modals.kanban.modal-footer modalId="{{$modalId}}" txtTypeId="txtTaskId" route="route_task"/>
@endsection

@section($modalId.'-javascript')
<script>
    setTimeout(() => kanbanLoadModalRenderer('txtTaskId', 'divTaskBody', route_task), 100);
</script>
@endsection