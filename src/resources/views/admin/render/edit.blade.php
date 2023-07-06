<div class="fixed inset-0 z-30 hidden items-center justify-center overflow-y-auto overflow-x-hidden outline-none focus:outline-none"
    id="modal-id">
    <div class="relative my-6 mx-auto w-auto max-w-3xl">
        <!--content-->
        <div
            class="relative flex w-full flex-col rounded-lg border-0 bg-white shadow-lg outline-none focus:outline-none">
            <form action="" method="post" id="form-edit">
                @method('PUT')
                @csrf
                <!--header-->
                <div class="flex items-start justify-between rounded-t border-b border-solid border-slate-200 p-3">
                    <h3 class="p-1 text-3xl font-semibold">
                        Edit Role ID
                    </h3>
                    <button
                        class="float-right ml-auto border-0 bg-transparent p-1 text-3xl font-semibold leading-none text-black opacity-5 outline-none focus:outline-none"
                        onclick="toggleModalPermission('modal-id')" type="button">
                        <span
                            class="block h-6 w-6 bg-transparent text-2xl text-black opacity-5 outline-none focus:outline-none">
                            ×
                        </span>
                    </button>
                </div>
                <!--body-->
                <div class="relative flex-auto p-6">
                    <div>
                        <label for="name_role"
                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Name</label>
                        <input type="text" name="name" id="name_role"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                            required>
                    </div>
                    <div>
                        <label for="guard_role"
                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Gruard</label>
                        <input type="text" name="guard_name" id="guard_role"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                            required>
                    </div>
                </div>
                <!--footer-->
                <div class="flex items-center justify-end rounded-b border-t border-solid border-slate-200 p-2">
                    <button
                        class="background-transparent mr-1 mb-1 px-6 py-2 text-sm font-bold uppercase text-red-500 outline-none transition-all duration-150 ease-linear focus:outline-none"
                        type="button" onclick="toggleModalPermission('modal-id')">
                        Close
                    </button>
                    <button
                        class="mr-1 mb-1 rounded bg-emerald-500 p-3 text-sm font-bold uppercase text-white shadow outline-none transition-all duration-150 ease-linear hover:shadow-lg focus:outline-none active:bg-emerald-600"
                        type="submit" onclick="toggleModalPermission('modal-id')">
                        Update Role
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
<div class="fixed inset-0 z-0 hidden bg-black opacity-25" id="modal-id-backdrop"></div>
<script type="text/javascript">
    function toggleModalPermission(modalID) {
        document.getElementById(modalID).classList.toggle("hidden");
        document.getElementById(modalID + "-backdrop").classList.toggle("hidden");
        document.getElementById(modalID).classList.toggle("flex");
        document.getElementById(modalID + "-backdrop").classList.toggle("flex");
    }
</script>
