<template x-if="isModalOpening['{{$modalId}}']">
    <div tabindex="-1" class="fixed sm:p-0 md:p-0 top-0 left-0 right-0 z-50 lg:p-4 h-full bg-gray-100 dark:bg-slate-400 dark:bg-opacity-70 bg-opacity-70 justify-center items-center flex" aria-hidden="true"  @keydown.escape="closeModal('{{$modalId}}')">
        <div class="relative my-20 sm:mx-0 sm:my-12 md:mx-16 md:my-48 w-full lg:mx-20 xl:mx-56 2xl:mx-96 h-auto md:h-auto sm:h-auto" @click.away="closeModal('{{$modalId}}')">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <div class="flex">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            @yield($modalId.'-header')
                        </h3>
                        <button type="button" @click="closeModal('{{$modalId}}')" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="large-modal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    @yield($modalId.'-header-extra')
                </div>
                <!-- Modal body -->
                <div class="px-6 overflow-y-auto h-[300px] md:h-[600px]" data-container>
                    @yield($modalId.'-body')
                    @sectionMissing($modalId.'-body')
                    <x-renderer.emptiness></x-renderer.emptiness>
                    @endif
                </div>
                <!-- Modal footer -->
                @yield($modalId.'-footer')
                @sectionMissing($modalId.'-footer')
                <div class="flex items-center justify-end rounded-b border-t border-solid border-slate-200 dark:border-gray-600 p-2">
                    {{-- <x-renderer.button class="mx-2" type='success'>OK</x-renderer.button> --}}
                    <x-renderer.button click="closeModal('{{$modalId}}')">Cancel</x-renderer.button>
                </div> 
                @endif
            </div>
        </div>
    </div>
    @yield($modalId.'-javascript')
</template>