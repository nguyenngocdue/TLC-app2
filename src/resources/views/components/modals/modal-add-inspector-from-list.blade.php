@php  @endphp
<template x-if="isListingTableOpen['{{$modalId}}']">
    <div tabindex="-1" class="fixed sm:p-0 md:p-0 top-0 left-0 right-0 z-50 lg:p-4 h-full bg-gray-100 dark:bg-slate-400 dark:bg-opacity-70 bg-opacity-70 justify-center items-center flex " aria-hidden="true" @keydown.escape="closeListingTable('{{$modalId}}')">
        <div class="relative sm:mx-0 md:mx-5  w-full lg:mx-48 xl:mx-56 2xl:mx-72 md:h-auto sm:h-auto h-auto"  @click.away="closeListingTable('{{$modalId}}')">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700 max-h-auto">
                <!-- Modal header -->
                <div class="items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <div class="flex">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Select inspector from list
                        </h3>
                        <button type="button" @click="closeListingTable('{{$modalId}}')" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="large-modal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                </div>
                <!-- Modal body -->
                <form action="{{route($type.'.sign_off.sendMail')}}" method="POST">
                    @csrf
                    <input type="hidden" value="{{$action}}" name="href">
                    <input type="hidden" value="{{$type}}" name="type">
                    <input type="hidden" value="{{$modalId}}" name="id">
                    <div class="h-[500px] overflow-y-auto overflow-x-hidden p-4">
                        <x-modals.inspector-radio-group />
                    </div>
                    <div class="flex items-center justify-end rounded-b border-t border-solid border-slate-200 dark:border-gray-600 p-2">
                       <x-renderer.button htmlType='submit' class="mx-2" type='success'>Choose</x-renderer.button>
                       <x-renderer.button click="closeListingTable('{{$modalId}}')">Cancel</x-renderer.button>
                    </div>  
                </form>
            </div>
        </div>
    </div>
</template>

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