
{{-- @extends("modals.modal-large") --}}
@extends("modals.modal-medium")
{{-- @extends("modals.modal-small") --}}

@section($modalId.'-header', "Header")

@section($modalId.'-body')
    <input id="txtPageId" type="hidden" x-bind:value="modalParams['{{$modalId}}']['id']">
    <div id="divPageBody" class="my-4"></div>
@endsection

@section($modalId.'-footer')
    <x-modals.kanban.modal-footer modalId="{{$modalId}}" txtTypeId="txtPageId" route="route_page"/>
@endsection

@section($modalId.'-javascript')
<script>
    setTimeout(() => kanbanLoadModalRenderer('txtPageId', 'divPageBody', route_page), 100);
</script>
@endsection