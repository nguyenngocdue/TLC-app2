@extends("modals.modal-large")

@section($modalId.'-header', "Select Items from a List")

@section($modalId.'-body')
    @php
        $fieldIdName = $table01Name . '_modal_input';
        $hidden = (app()->isLocal()) ? '' : 'hidden';
    @endphp
    <div class="h-4"></div>
    <div id="divModalDataLoading"><i class="fa-duotone fa-spinner fa-spin text-green-500 px-2"></i>Loading...</div>
    <script>inputId = '{{$fieldIdName}}';</script>
    <div id="json_tree_1"></div>
    <script src="{{asset('js/modals/get_lines/'.$modalBodyName.".js")}}"></script>
@endsection

@section($modalId.'-footer')
<div class="flex items-center justify-end rounded-b border-t border-solid border-slate-200 dark:border-gray-600 p-2">
    <input id="{{$fieldIdName}}" 
        type="{{$hidden}}" 
        class="bg-pink-400 p-1 mr-2 border rounded w-full" 
        />
    <x-renderer.button 
        click="closeModal('{{$modalId}}')"
        >Cancel</x-renderer.button>
    <x-renderer.button 
        class="mx-2" 
        type='success' 
        click="loadListToTable(addLinesToTableFormModalList, '{{$fieldIdName}}', '{{$table01Name}}', '{{$xxxForeignKeys}}', '{{$dataTypeToGetId}}', '{{$modalId}}')"
        >Populate</x-renderer.button>
</div>
@endsection

@section($modalId.'-javascript')
<script src="{{asset('js/modals/modal-add-from-a-list.js')}}"></script>
<script> jsonTree = [] </script>
@endsection