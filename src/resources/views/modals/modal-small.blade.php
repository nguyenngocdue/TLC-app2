<div @click.stop>{{-- prevent the click event of dropdown multi from propagating up to the modal --}}
    <x-modals.modal-parent modalId="{{$modalId}}" modalClass="w-1/3" height="300"/>
</div>