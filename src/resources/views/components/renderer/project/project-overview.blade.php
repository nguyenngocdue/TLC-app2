<x-renderer.card title="Project Milestones" class="bg-white border" icon="fa-sharp fa-regular fa-users" >
    <x-renderer.table showNo=1 :columns="$columns" :dataSource="$dataSource" />
    <x-modals.modal-over-due-documents modalId="modalId"></x-modals.modal-over-due-documents>
</x-renderer.card>