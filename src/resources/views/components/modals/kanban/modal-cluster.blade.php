
{{-- @extends("modals.modal-large") --}}
@extends("modals.modal-medium")
{{-- @extends("modals.modal-small") --}}

@section($modalId.'-header', "Header")

@section($modalId.'-body')
    <input id="textToBeLoadedIds" type="hidden1" x-bind:value="modalParams['{{$modalId}}']['id']">
    <div class="p-2">
        Cluster Body
    </div>
@endsection

{{-- @section($modalId.'-footer')
@endsection --}}

@section($modalId.'-javascript')
<script>
    
</script>
@endsection