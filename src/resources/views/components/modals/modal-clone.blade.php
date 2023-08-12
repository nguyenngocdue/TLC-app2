
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
    <x-renderer.button class="mx-1" type='success' onClick="callApiCloneTemplate1()">Clone Template</x-renderer.button>
</div>
@endsection

@section($modalId.'-javascript')
<script>
    url = @json($url);
    callApiCloneTemplate1 = () => {
        tmpl_id = document.getElementById('template_id').value
        if(tmpl_id){
           callApiCloneTemplate(url, [{tmpl_id}])
        }else{
            console.log("What is the selected template?")
        }
    }
</script>
@endsection