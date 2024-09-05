{{-- searchable dialog {{$multipleStr}} --}}
<div id="div_value_{{$name}}"></div>

<div class="flex w-full gap-1">
    <div id="div_text_{{$name}}" class="readonly w-full fle1x items-center p-1"></div>
    <x-renderer.button 
        class="z-10"
        click="toggleModal('modal-searchable-dialog-{{$id}}')"
        keydownEscape="closeModal('modal-searchable-dialog-{{$id}}')"
        >...</x-renderer.button>
</div>

<x-controls.has-data-source.modal-searchable-dialog 
    modalId="modal-searchable-dialog-{{$id}}"
    tableName="{{$table}}"
    multipleStr="{{$multipleStr}}"
    divValueName="div_value_{{$name}}"
    divTextName="div_text_{{$name}}"
    inputName="{{$name}}"
    />

<script>
    $(`[id="div_text_{{$name}}"]`).html(@json($selectedArr).map(v=>renderTag(v)).join(''));
    $(`[id="div_value_{{$name}}"]`).html(@json($selected).map(v=>renderInputField('{{$name}}',v)).join(''));
</script>