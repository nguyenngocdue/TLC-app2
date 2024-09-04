searchable dialog {{$multipleStr}}
<div class="flex w-full gap-1">
    <input type="text" class="{{$classList}} readonly" readonly value="Ahihi" tabindex="-1"/>
    <x-renderer.button 
        click="toggleModal('modal-searchable-dialog-{{$id}}')"
        keydownEscape="closeModal('modal-searchable-dialog-{{$id}}')"
        >...</x-renderer.button>
</div>

<x-controls.has-data-source.modal-searchable-dialog 
    modalId="modal-searchable-dialog-{{$id}}"
    tableName="{{$table}}"
    />