@php
    $idInput = $type.'_checkbox_all';
    $nameInput = $type. '[]';
    $urlDuplicate = route($type . '_dp.duplicateMultiple') ?? '';
    $urlRestore = route($type . '.restoreMultiple') ?? '';
    $urlDestroy = route($type . '.destroyMultiple') ?? '';
@endphp
<div class="flex">
    <x-renderer.button size='xs'  type='primary' onClick="actionCheckboxAll('{{$type}}')" >
        <input id="{{$idInput}}" type="checkbox" name="{{$nameInput}}" value="none"/>
            Select All
    </x-renderer.button>
    @if($restore)
    <x-renderer.button size='xs' type='success' onClick="actionMultiple('{{$type}}','{{$urlRestore}}','restored')" class="mx-1" ><i class='fa-light fa-trash-can-arrow-up'></i> Restore</x-renderer.button>
    @else
    <x-renderer.button size='xs' type='secondary' onClick="actionMultiple('{{$type}}','{{$urlDuplicate}}','duplicated')" class="mx-1" ><i class='fa fa-copy'></i> Duplicate</x-renderer.button>
    <x-renderer.button size='xs' type='danger' onClick="actionMultiple('{{$type}}','{{$urlDestroy}}','deleted')" ><i class='fa fa-trash'></i> Delete</x-renderer.button>
    @endif
</div>