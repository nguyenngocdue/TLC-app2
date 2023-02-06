<div class="fixed right-0 top-8 z-20 hidden items-center justify-center outline-none focus:outline-none" id="modal-setting-id">
    <div class="relative my-6 mx-auto h-full w-[750px]">
        <!--content-->
        <div class="relative flex h-full w-full flex-col border rounded-md bg-white dark:bg-gray-700 dark:border-gray-600 shadow-lg outline-none focus:outline-none">
            <!--header-->
            <div class="flex h-14 items-start justify-between rounded-t border-b border-solid dark:border-gray-600 p-3">
                <h3 class="p-1 text-2xl font-semibold text-black dark:text-white">
                    {{$title}}
                </h3>
                <button class="float-right ml-auto border-1 bg-transparent p-1 text-3xl font-semibold leading-none text-black dark:text-white opacity-70 outline-none focus:outline-none" onclick="toggleModalSetting('modal-setting-id')" type="button">
                    <span class="block h-6 w-6 bg-transparent text-2xl text-black dark:text-white outline-none focus:outline-none">
                        ×
                    </span>
                </button>
            </div>
            <!--body-->
            {{$slot}}
        </div>
    </div>
</div>