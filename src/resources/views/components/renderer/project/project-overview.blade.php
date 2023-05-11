@php $modalId = "modal-over-due-documents"; @endphp
<x-renderer.card title="Project Milestones" class="bg-white border" icon="fa-sharp fa-regular fa-users" >
    <x-renderer.table showNo=1 :columns="$columns" :dataSource="$dataSource" />
    <x-modals.modal-over-due-documents modalId="{{$modalId}}"></x-modals.modal-over-due-documents>
    <x-renderer.button keydown="closeModal('{{$modalId}}')"  click="toggleModal('{{$modalId}}')">Open</x-renderer.button>
    
    <x-modals.modal-empty modalId="{{$modalId}}1"></x-modals.modal-empty>
    <x-renderer.button keydown="closeModal('{{$modalId}}1')"  click="toggleModal('{{$modalId}}1')">Open E</x-renderer.button>
</x-renderer.card>