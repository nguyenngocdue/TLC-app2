<div class="flex items-center justify-between rounded-b border-t border-solid border-slate-200 dark:border-gray-600 p-2">
    <div>
        <x-renderer.button type="danger" icon="fa-duotone fa-trash"
            click="
                kanbanDeleteItem('{{$txtTypeId}}', {{$route}}, '{{$prefix}}');
                closeModal('{{$modalId}}');
                ">
            Delete
        </x-renderer.button>
    </div>
    <div>
        <x-renderer.button click="closeModal('{{$modalId}}')">Cancel</x-renderer.button>
        <x-renderer.button class="mx-2" type='success' icon="fa-duotone fa-floppy-disk" 
            click="
                kanbanUpdateItem('{{$txtTypeId}}', {{$route}}, '{{$captionType}}', '{{$txtType}}');
                closeModal('{{$modalId}}');
                ">
            Save
        </x-renderer.button>
    </div>
</div>