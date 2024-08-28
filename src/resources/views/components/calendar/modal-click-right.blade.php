<div id="modal-click-right" class="hidden fixed flex z-10 items-center justify-center">
    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700 w-72">
        <button type="button" onclick="moveEventTo(this,'morning')" value="" class="set-morning-button inline-flex justify-between items-center w-full px-5 py-2 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200">
            <span>Move to Morning</span>
            <i class="ml-2 fa-solid fa-arrow-up"></i>
        </button>
        <button type="button" onclick="moveEventTo(this,'afternoon')" value="" class="set-afternoon-button inline-flex justify-between items-center w-full px-5 py-2 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200">
            <span>Move to Afternoon</span>
            <i class="ml-2 fa-solid fa-arrow-down"></i>
        </button>
        <button type="button" onclick="moveEventTo(this,'full_day')" value="" class="set-full-day-button inline-flex justify-between items-center w-full px-5 py-2 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200">
            <span>Move to Full Day</span>
            <i class="ml-2 fa-solid fa-arrow-down"></i>
        </button>
        <button type="button" onclick="duplicateEvent(this)" value="" class="duplicate-button inline-flex justify-between items-center w-full px-5 py-2 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200">
            <span>Duplicate to the next day</span>
            <i class="ml-2 fa fa-copy text-blue-500"></i>
        </button>
        <button type="button" onclick="deleteEvent(this)" value="" class="delete-button inline-flex justify-between items-center w-full px-5 py-2 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200">
            <span>Delete</span>
            <i class="ml-2 fa fa-trash text-red-500"></i>
        </button>
    </div>
</div>