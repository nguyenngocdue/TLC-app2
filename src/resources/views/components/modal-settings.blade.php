<x-renderer.button type="secondary" outline=true title="Screen Settings" click="toggleSettingMenu" keydownEscape="closeSettingMenu" accesskey="s">
    <i class="fa-duotone fa-gear"></i>
</x-renderer.button>
  <template x-if="isSettingMenuOpen">
    <div tabindex="-1" class="fixed sm:p-0 md:p-0 top-0 left-0 right-0 z-50 lg:p-4 h-full bg-gray-100 dark:bg-slate-400 dark:bg-opacity-70 bg-opacity-70 justify-center items-center flex" aria-hidden="true" @keydown.escape="closeSettingMenu">
        <div class="relative sm:mx-0 md:mx-10  w-full lg:mx-20 xl:mx-56 2xl:mx-96 h-auto md:h-auto sm:h-auto" @click.away="">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <div class="flex">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{$title}}
                        </h3>
                        <button type="button" @click="closeSettingMenu" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="large-modal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                </div>
                <!-- Modal body -->
                <form action="{{ route('updateUserSettings') }}" method="post">
                    @method('PUT')
                    @csrf
                    <div class='overflow-y-scroll p-4'>
                        <label class="py-5 text-base font-medium text-black dark:text-white">Columns</label>
                        <div class="grid grid-cols-5 gap-3">
                            <input type="hidden" name='_entity' value="{{ $type }}">
                            <input type="hidden" name='action' value="updateGear">
                            @forelse ($allColumns as $key => $value)
                                <div class="truncate">
                                    <label class="text-black dark:text-white">
                                        <input type="checkbox" name="{{ $key }}" @checked(array_key_exists($key, $selected))>
                                        {{ $value['label'] }}
                                    </label>
                                </div>
                            @empty
                                There is no prop to be found
                            @endforelse
                        </div>
                    </div>
                    <div class="flex items-center justify-end rounded-b border-t border-solid border-slate-200 dark:border-gray-600 p-2">
                        <button class="mr-2 mb-1 rounded bg-emerald-500 p-3 text-sm font-bold uppercase text-white shadow outline-none transition-all duration-150 ease-linear hover:shadow-lg focus:outline-none active:bg-emerald-600" type="submit">
                            Update
                        </button>
                    </div>                
                </form>
            </div>
        </div>
    </div>
  </template>
  
  
  