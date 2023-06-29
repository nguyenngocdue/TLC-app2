@extends("modals.modal-large")

@section($modalId.'-header', "Select members from list")

@section($modalId.'-body')
    <div class="h-4"></div>
    <x-modals.parent-type7-user-ot name='ot_team_id' tableName="ot_teams" />
    <div class="py-2">
        <x-renderer.button onClick="radioOrCheckboxSelectAll('ot_user_id')">Select All</x-renderer.button>
        <x-renderer.button onClick="radioOrCheckboxDeselectAll('ot_user_id')">Deselect All</x-renderer.button>
        <x-renderer.button onClick="radioOrCheckboxChangeOrder('ot_user_id', 'ot_users', 'ot_team_id')">Sort by StaffID Order</x-renderer.button>
    </div>
    <x-modals.parent-id7-user-ot name='ot_user_id' tableName="ot_users" multiple={{true}} control='radio-or-checkbox2' />
@endsection

@section($modalId.'-footer')
<div class="flex items-center justify-end rounded-b border-t border-solid border-slate-200 dark:border-gray-600 p-2">
    <x-renderer.button click="closeModal('{{$modalId}}')">Cancel</x-renderer.button>
    <x-renderer.button class="mx-2" type='success' click="loadListToTable(addLinesToTable,'ot_user_id', '{{$modalId}}')">Populate</x-renderer.button>
</div>
@endsection

@section($modalId.'-javascript')
<script>
const addLinesToTable = (listId, tableId) => {
    const x = $("input[name='" + listId + "[]']")
    const result = []
    for (let i = 0; i < x.length; i++) {
        if (x[i].checked) result.push(x[i].value)
    }
    // console.log(result)
    for (let i = 0; i < result.length; i++) {
        const user_id = result[i]
        const today = moment().format('DD/MM/YYYY')
        const valuesOfOrigin = { user_id, ot_date: today }
        // console.log("Add line", tableId, valuesOfOrigin)
        addANewLine({ tableId, valuesOfOrigin, isDuplicatedOrAddFromList:true, batchLength: result.length })
    }
}
</script>
@endsection