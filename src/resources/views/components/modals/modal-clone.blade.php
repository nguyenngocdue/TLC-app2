
@extends("modals.modal-medium")

@section($modalId.'-header', "Create a Document from a Template")

@section($modalId.'-body')
    <div class="p-2">
        <label for="template_id" class="py-2 text-gray-700">Choose a Template to Clone</label>
        <div class="py-1"></div>
        <x-modals.dropdown5 name="template_id" :dataSource="$dataSource"></x-modals.dropdown5>
    </div>
@endsection

@section($modalId.'-footer')
<div class="flex items-center justify-end rounded-b border-t border-solid border-slate-200 dark:border-gray-600 p-2">
    <x-renderer.button click="closeModal('{{$modalId}}')">Cancel</x-renderer.button>
    <x-renderer.button class="mx-1" type='success' click="callApiCloneTemplate()">Clone Template</x-renderer.button>
</div>
@endsection

@section($modalId.'-javascript')
<script>
    url = @json($url);
    callApiCloneTemplate = () => {
        id = document.getElementById('template_id').value
        if(id){
            $.ajax({
            type: 'post',
            url: url,
            data: { id: id },
            success: (response) => {
                toastr.success(response.message);
                window.location.href = response.href;
            },
            error: (jqXHR) => {
                toastr.error(jqXHR.responseJSON.message);
            },
            }) 
        }
    }
</script>
@endsection