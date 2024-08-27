<div class="relative inline-block text-left">
    <!-- Dropdown Button -->
    <button id="dropdownButton" class="flex items-center justify-between p-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-400">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v18h16.5V3H3.75zm0 0L12 12m0 0l8.25-9m-8.25 9L3.75 21" />
        </svg>
        <span class="ml-2">Time Range</span>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-400 ml-auto">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 15l-7.5 7.5L4.5 15" />
        </svg>
    </button>

    <!-- Dropdown Content (Initially Hidden) -->
    <div id="dropdownContent" class="hidden absolute z-10 mt-2 p-4 bg-white rounded-lg shadow-lg border border-gray-300 w-[500px] transform -translate-x-full left-16">
        <!-- Container for two-column layout -->
        <div class="grid grid-cols-2 border-b">
            <!-- Left column: From-To fields -->
            <div class="pr-4 border-r border-gray-300">
                <form method="POST" action="{{ url('/your-action-url') }}">
                    @csrf <!-- Add CSRF token for Laravel form submission -->
                    <div class="mb-4">
                        <h2 class="font-semibold text-lg mb-2">Absolute time range</h2>
                        <div class="space-y-2">
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700">From</label>
                                <input type="text" name="fromDate" id="fromDate" placeholder="now/y" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700">To</label>
                                <input type="text" name="toDate" id="toDate" placeholder="now/y" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                        </div>
                        <button type="submit" class="mt-4 w-full bg-blue-600 text-white py-2 px-4 rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Apply time range</button>
                    </div>
                </form>
            </div>

            <!-- Right column: Quick range selection dropdown -->
            <div class="pl-4 pb-2">
                <input type="text" placeholder="Search quick ranges" class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="quickRangeInput">
                <div class="mt-2 w-full bg-white border border-gray-300 rounded-md shadow-lg max-h-80 overflow-y-scroll z-10" id="quickRangeDropdown">
                    <ul class="py-1 text-gray-700" id="quickRangeList">
                        <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Last 5 minutes</li>
                        <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Last 15 minutes</li>
                        <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Last 30 minutes</li>
                        <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Last 1 hour</li>
                        <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Last 3 hours</li>
                        <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Last 6 hours</li>
                        <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Last 12 hours</li>
                        <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Last 24 hours</li>
                        <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Last 2 days</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- JavaScript to toggle dropdown visibility -->
<script>
    document.getElementById('dropdownButton').addEventListener('click', function() {
        var dropdownContent = document.getElementById('dropdownContent');
        dropdownContent.classList.toggle('hidden');
    });
</script>

    <!-- Add Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Initialize Flatpickr for the 'From' and 'To' date inputs
        flatpickr("#fromDate", {
            dateFormat: "Y-m-d",
            allowInput: true,
            onChange: function(selectedDates, dateStr, instance) {
                // Optional: handle the selected date
            }
        });

        flatpickr("#toDate", {
            dateFormat: "Y-m-d",
            allowInput: true,
            onChange: function(selectedDates, dateStr, instance) {
                // Optional: handle the selected date
            }
        });
    </script>  

<style>
#quickRangeDropdown {
    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none;  /* IE and Edge */
    transition: scrollbar-width 0.5s, -ms-overflow-style 0.5s, -webkit-scrollbar 0.5s; /* Add transition for smooth effect */
}

#quickRangeDropdown::-webkit-scrollbar {
    display: none; /* Safari and Chrome */
    transition: opacity 0.5s ease-in-out; /* Smooth transition for Chrome/Safari */
    opacity: 0; /* Start hidden */
}
</style>

<script>
    const dropdown = document.getElementById('quickRangeDropdown');

    // Show the scrollbar when scrolling
    dropdown.addEventListener('scroll', function() {
        dropdown.style.scrollbarWidth = 'thin'; /* Firefox */
        dropdown.style['-ms-overflow-style'] = 'auto';  /* IE and Edge */
        dropdown.style['-webkit-scrollbar'] = 'initial'; /* Safari and Chrome */

        // Hide scrollbar after scrolling stops
        clearTimeout(dropdown.hideScrollbarTimeout);
        dropdown.hideScrollbarTimeout = setTimeout(function() {
            dropdown.style.scrollbarWidth = 'none'; /* Firefox */
            dropdown.style['-ms-overflow-style'] = 'none';  /* IE and Edge */
            dropdown.style['-webkit-scrollbar'] = 'none'; /* Safari and Chrome */
        }, 1000); // Adjust the delay (in milliseconds) as needed
    });
</script>
