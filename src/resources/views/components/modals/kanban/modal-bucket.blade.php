
{{-- @extends("modals.modal-large") --}}
@extends("modals.modal-medium")
{{-- @extends("modals.modal-small") --}}

@section($modalId.'-header', "Header")

@section($modalId.'-body')
    <input id="txtBucketId" type="hidden" x-bind:value="modalParams['{{$modalId}}']['id']">
    <div id="divBucketBody" class="my-4"></div>
@endsection

@section($modalId.'-footer')
    <x-modals.kanban.modal-footer 
        modalId="{{$modalId}}" 
        txtTypeId="txtBucketId" 
        route="route_bucket" 
        captionType="caption_bucket" 
        txtType="txt_bucket"
        prefix="bucket_parent_"
        />
@endsection

@section($modalId.'-javascript')
<script>
    setTimeout(() => kanbanLoadModalRenderer('txtBucketId', 'divBucketBody', route_bucket), 100);
</script>
@endsection