<template x-if="isModalOpening['{{$modalId}}']">
    <div id="{{$modalId}}" style="background-color: rgba(0, 0, 0, 0.8);" tabindex="-1" class="fixed sm:p-0 p-0 lg:p-4 top-0 left-0 right-0 bottom1-0 z-40 h-full dark:bg-slate-400 dark:bg-opacity-70 bg-opacity-70 justify-center items-center flex" aria-hidden1="true" @keydown.escape="closeModal('{{$modalId}}')">
        <div class="relative {{$modalClass}} h-auto" @click.away="closeModal('{{$modalId}}')">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <div class="flex">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            @yield($modalId.'-header')
                        </h3>
                        <button type="button" @click="closeModal('{{$modalId}}')" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-full text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="large-modal">
                            <i class="fa-sharp fa-solid fa-xmark w-6 h-6 text-base"></i>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    @yield($modalId.'-header-extra')
                </div>
                <!-- Modal body -->
                <div class="px-6 overflow-y-auto h-[400px] md:h-[600px]" style="height:{{$height??""}}px" data-container>
                    @yield($modalId.'-body')
                    @sectionMissing($modalId.'-body')
                    <x-renderer.emptiness></x-renderer.emptiness>
                    @endif
                </div>
                <!-- Modal footer -->
                @yield($modalId.'-footer')
                @sectionMissing($modalId.'-footer')
                <div class="flex items-center justify-end rounded-b border-t border-solid border-slate-200 dark:border-gray-600 p-2">
                    <div>
                        <x-renderer.button click="closeModal('{{$modalId}}')">Cancel</x-renderer.button>
                        {{-- <x-renderer.button type='success'>OK</x-renderer.button> --}}
                    </div>
                </div> 
                @endif
            </div>
        </div>
        @yield($modalId.'-javascript')
    </div>
</template>