
{{-- @extends("modals.modal-large") --}}
@extends("modals.modal-medium")
{{-- @extends("modals.modal-small") --}}

@section($modalId.'-header', "Header")

@section($modalId.'-body')
    <input id="txtGroupId" type="hidden" x-bind:value="modalParams['{{$modalId}}']['id']">
    <div id="divGroupBody" class="my-4"></div>
@endsection

@section($modalId.'-footer')
    <x-modals.kanban.modal-footer modalId="{{$modalId}}" txtTypeId="txtGroupId" route="route_group" captionType="caption_group" txtType="txt_group"/>
@endsection

@section($modalId.'-javascript')
<script>
    setTimeout(() => kanbanLoadModalRenderer('txtGroupId', 'divGroupBody', route_group), 100);
</script>
@endsection