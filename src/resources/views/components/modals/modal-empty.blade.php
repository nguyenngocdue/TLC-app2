
@extends("modals.modal-large")
{{-- @extends("modals.modal-medium") --}}
{{-- @extends("modals.modal-small") --}}

@section($modalId.'-header', "Header")

@section($modalId.'-body')
    {{-- <input id="textToBeLoadedIds" type="hidden" x-bind:value="modalParams['{{$modalId}}']['ids']"> --}}
    <div class="p-2">
        Body
    </div>
@endsection

{{-- @section($modalId.'-footer')
<div class="flex items-center justify-end rounded-b border-t border-solid border-slate-200 dark:border-gray-600 p-2">
    <x-renderer.button click="closeModal('{{$modalId}}')">Cancel</x-renderer.button>
    <x-renderer.button class="mx-2" type='success'>OK</x-renderer.button>
</div>
@endsection --}}

@section($modalId.'-javascript')
<script>
    
</script>
@endsection