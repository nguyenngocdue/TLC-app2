<template x-if="isListingTableOpen['{{$modalId}}']" x-on:keydown.escape="console.log(1111)">
    <div tabindex="-1" class="fixed sm:p-0 md:p-0 top-0 left-0 right-0 z-50 lg:p-4 h-full bg-gray-100 dark:bg-slate-400 dark:bg-opacity-70 bg-opacity-70 justify-center items-center flex" aria-hidden="true" @keydown.escape="closeListingTable('{{$modalId}}')">
        <div class="relative sm:mx-0 md:mx-5  w-full lg:mx-10 xl:mx-16 2xl:mx-20 h-auto md:h-auto sm:h-auto"  @click.away="closeListingTable('{{$modalId}}')">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700 h-full">
                <!-- Modal header -->
                <div class="items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <div class="flex">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Select members from list
                        </h3>
                        <button type="button" @click="closeListingTable('{{$modalId}}')" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="large-modal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                </div>
                <!-- Modal body -->
                <div class="h-[300px]">
                    <div class="overflow-y-scroll p-4">
                        <h1>ABC</h1>
                        <h1>ABC</h1>
                        <h1>ABC</h1>
                        <h1>ABC</h1>
                        <h1>ABC</h1>
                        <h1>ABC</h1>
                        <h1>ABC</h1>
                        <h1>ABC</h1>
                        <h1>ABC</h1>
                        <h1>ABC</h1>
                        <h1>ABC</h1>
                        <h1>ABC</h1>
                        <h1>ABC</h1>
                        <h1>ABC</h1>
                        <h1>ABC</h1>
                        <h1>ABC</h1>
                        <h1>ABC</h1>
                        <h1>ABC</h1>
                        <h1>ABC</h1>
                        <h1>ABC</h1>
                        <h1>ABC</h1>
                        <h1>ABC</h1>
                        <h1>ABC</h1>
                        <h1>ABC</h1>
                        <h1>ABC</h1>
                        <h1>ABC</h1>
                        <h1>ABC</h1>
                        <h1>ABC</h1>
                        <h1>ABC</h1>
                        <h1>ABC</h1>
                        <h1>ABC</h1>
                        <h1>ABC</h1>
                        <h1>ABC</h1>
                        <h1>ABC</h1>
                        <h1>ABC</h1>
                        <h1>ABC</h1>
                        <h1>ABC</h1>
                        {{-- <x-modals.parent-type7-user-ot name='modal_ot_team'></x-modals.parent-type7> --}}
                        {{-- <x-modals.parent-id7-user-ot name='modal_ot_user1' multiple={{false}} control='radio-or-checkbox2'></x-modals.parent-type7> --}}
                        {{-- <x-modals.parent-id7-user-ot name='modal_ot_user2' multiple={{true}} control='radio-or-checkbox2'></x-modals.parent-type7> --}}
                        {{-- <x-modals.parent-id7-user-ot name='modal_ot_user3' multiple={{false}}></x-modals.parent-type7> --}}
                        {{-- <x-modals.parent-id7-user-ot name='modal_ot_user4' multiple={{true}}></x-modals.parent-type7> --}}
                    </div>
                </div>
                <div class="flex items-center justify-end rounded-b border-t border-solid border-slate-200 dark:border-gray-600 p-2">
                   <x-renderer.button class="mx-2" type='success'>Populate</x-renderer.button>
                   <x-renderer.button type='default' click="closeListingTable('{{$modalId}}')">Cancel</x-renderer.button>
                </div>                
            </div>
        </div>
    </div>
</template>