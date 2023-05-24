@extends("modals.modal-big")

@section($modalId.'-header', "Select members from list")

@section($modalId.'-body')
    <div class="h-4"></div>
    <x-modals.parent-type7-user-ot name='ot_team'></x-modals.parent-type7>
    <div class="py-2">
        <x-renderer.button onClick="radioOrCheckboxSelectAll('ot_user2')">Select All</x-renderer.button>
        <x-renderer.button onClick="radioOrCheckboxDeselectAll('ot_user2')">Deselect All</x-renderer.button>
        <x-renderer.button onClick="radioOrCheckboxChangeOrder('ot_user2', 'modal_ot_user2')">Sort by StaffID Order</x-renderer.button>
    </div>
    <x-modals.parent-id7-user-ot name='ot_user2' multiple={{true}} control='radio-or-checkbox2'></x-modals.parent-type7>
@endsection

@section($modalId.'-footer')
<div class="flex items-center justify-end rounded-b border-t border-solid border-slate-200 dark:border-gray-600 p-2">
    <x-renderer.button class="mx-2" type='success' click="loadListToTable(addLinesToTable,'ot_user2', '{{$modalId}}')">Populate</x-renderer.button>
    <x-renderer.button click="closeModal('{{$modalId}}')">Cancel</x-renderer.button>
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