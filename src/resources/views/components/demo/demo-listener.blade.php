<x-renderer.card title="Listen Reduce">
    <div class="grid grid-cols-12">
        <div class="col-span-12">
            Parent: <x-modals.parent-type7 name='modal_ot_team' selected1='1003-a' allowClear={{true}}></x-modals.parent-type7>
        </div>
        <div class="col-span-6">
            <div class="grid grid-cols-12">
                <div class="col-span-3">
                    Radio
                </div>
                <div class="col-span-9">
                    <x-modals.parent-id7 name='modal_ot_user1' multiple={{false}} control='radio-or-checkbox2' selected="[2002]">
                        </x-modals.parent-type7>
                </div>
            </div>
        </div>
        <div class="col-span-6">
            <div class="grid grid-cols-12">
                <div class="col-span-3">
                    Checkbox
                </div>
                <div class="col-span-9">
                    <x-modals.parent-id7 name='modal_ot_user2' multiple={{true}} control='radio-or-checkbox2' selected="['2002','2003']">
                        </x-modals.parent-type7>
                </div>
            </div>
        </div>
        <div class="col-span-6">
            <div class="grid grid-cols-12">
                <div class="col-span-3">
                    Dropdown
                </div>
                <div class="col-span-9">
                    <x-modals.parent-id7 name='modal_ot_user3' multiple={{false}} allowClear="true" selected="[2003]">
                        </x-modals.parent-type7>
                </div>
            </div>
        </div>
        <div class=" col-span-6">
            <div class="grid grid-cols-12">
                <div class="col-span-3">
                    Dropdown Multi
                </div>
                <div class="col-span-9">
                    <x-modals.parent-id7 name='modal_ot_user4' multiple={{true}} selected="[2001,2002]">
                        </x-modals.parent-type7>
                </div>
            </div>
        </div>
    </div>
</x-renderer.card>
