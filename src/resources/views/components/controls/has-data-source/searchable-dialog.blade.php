{{-- searchable dialog {{$multipleStr}} {{$selectedStr}} --}}
@foreach($selected as $item)
<input id="{{$id}}" name="{{$name}}" type="hidden" readonly1 value="{{$item}}"tabindex="-1" />
@endforeach

<div class="flex w-full gap-1">
    <div class="readonly w-full fle1x items-center p-1">
        {!! $selectedStr !!}
    </div>
    <x-renderer.button 
        click="toggleModal('modal-searchable-dialog-{{$id}}')"
        keydownEscape="closeModal('modal-searchable-dialog-{{$id}}')"
        >...</x-renderer.button>
</div>

<x-controls.has-data-source.modal-searchable-dialog 
    modalId="modal-searchable-dialog-{{$id}}"
    tableName="{{$table}}"
    multipleStr="{{$multipleStr}}"
    selectedStr="{!! $selectedStr !!}"
    :selected="$selected"
    />