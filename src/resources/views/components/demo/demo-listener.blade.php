<x-renderer.card title="Listen Reduce">
    <div class="grid grid-cols-12">
        <div class="col-span-12">
            Parent: <x-modals.parent-type7 name='modal_ot_team'></x-modals.parent-type7>
        </div>
        <div class="col-span-6">
            <div class="grid grid-cols-12">
                <div class="col-span-3">
                    Radio
                </div>
                <div class="col-span-9">
                    <x-modals.parent-id7 name='modal_ot_user1' multiple={{false}} control='radio-or-checkbox2'></x-modals.parent-type7>
                </div>
            </div>
        </div>
        <div class="col-span-6">
            <div class="grid grid-cols-12">
                <div class="col-span-3">
                    Checkbox
                </div>
                <div class="col-span-9">
                    <x-modals.parent-id7 name='modal_ot_user2' multiple={{true}} control='radio-or-checkbox2'></x-modals.parent-type7>
                </div>
            </div>
        </div>
        <div class="col-span-6">
            <div class="grid grid-cols-12">
                <div class="col-span-3">
                    Dropdown
                </div>
                <div class="col-span-9">
                    <x-modals.parent-id7 name='modal_ot_user3' multiple={{false}}></x-modals.parent-type7>
                </div>
            </div>
        </div>
        <div class="col-span-6">
            <div class="grid grid-cols-12">
                <div class="col-span-3">
                    Dropdown Multi
                </div>
                <div class="col-span-9">
                    <x-modals.parent-id7 name='modal_ot_user4' multiple={{true}}></x-modals.parent-type7>
                </div>
            </div>
        </div>
    </div>
</x-renderer.card>