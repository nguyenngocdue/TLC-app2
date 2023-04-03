@if(app()->isLocal())
<div class="flex">
    <x-renderer.button size='xs' value='$name' type='default' onClick='actionDuplicated()' >
        <input id="{{$type}}_checkbox_all" type="checkbox">
            Check all
    </x-renderer.button>
    <x-renderer.button size='xs' value='$name' type='secondary' onClick='actionDuplicated()' ><i class='fa fa-copy'></i> Duplicate All</x-renderer.button>
    <x-renderer.button size='xs' value='$name' type='danger' onClick='actionDeleted()' ><i class='fa fa-trash'></i> Delete All</x-renderer.button>
</div>
@endif