@props(['groupWidth'])

{{-- @extends("modals.modal-large") --}}
@extends("modals.modal-medium")
{{-- @extends("modals.modal-small") --}}

@section($modalId.'-header', "Header")

@section($modalId.'-body')
    <input id="txtClusterId" type="hidden" x-bind:value="modalParams['{{$modalId}}']['id']">
    <div id="divClusterBody" class="my-4"></div>
@endsection

@section($modalId.'-footer')
    <x-modals.kanban.modal-footer 
        modalId="{{$modalId}}" 
        groupWidth="{{$groupWidth}}"
        txtTypeId="txtClusterId" 
        route="route_cluster" 
        prefix="cluster_parent_"
        />
@endsection

@section($modalId.'-javascript')
<script>
    setTimeout(() => kanbanLoadModalRenderer('txtClusterId', 'divClusterBody', route_cluster), 100);
</script>
@endsection