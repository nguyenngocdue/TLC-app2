<x-renderer.card title="Listen Reduce">
    <div class="grid grid-cols-12">
        <div class="col-span-12">
            Parent: <x-modals.parent-type7 name='ot_team_id' tableName="fake_ot_teams" selected1='1003-a' allowClear={{true}} />
        </div>
        <div class="col-span-6">
            <div class="grid grid-cols-12">
                <div class="col-span-3">
                    Radio
                </div>
                <div class="col-span-9">
                    <x-modals.parent-id7 name='ot_user1' tableName="fake_ot_users"  multiple={{false}} control='radio-or-checkbox2' selected='"2002"' />
                </div>
            </div>
        </div>
        <div class="col-span-6">
            <div class="grid grid-cols-12">
                <div class="col-span-3">
                    Checkbox
                </div>
                <div class="col-span-9">
                    <x-modals.parent-id7 name='ot_user2' tableName="fake_ot_users"  multiple={{true}} control='radio-or-checkbox2' selected='[2002,"2003"]' />
                </div>
            </div>
        </div>
        <div class="col-span-6">
            <div class="grid grid-cols-12">
                <div class="col-span-3">
                    Dropdown
                </div>
                <div class="col-span-9">
                    <x-modals.parent-id7 name='ot_user3' tableName="fake_ot_users"  multiple={{false}} allowClear="true" selected="[2003]" />
                </div>
            </div>
        </div>
        <div class=" col-span-6">
            <div class="grid grid-cols-12">
                <div class="col-span-3">
                    Dropdown Multi
                </div>
                <div class="col-span-9">
                    <x-modals.parent-id7 name='ot_user4' tableName="fake_ot_users"  multiple={{true}} selected='[2001,"2002"]' />
                </div>
            </div>
        </div>
    </div>
</x-renderer.card>
