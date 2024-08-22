@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

    <div class="p-4 bg-white rounded-lg shadow-lg mx-auto max-w-lg h-[400px] ">
        <!-- Container for two-column layout -->
        <div class="grid grid-cols-2 border-b">
            <!-- Left column: From-To fields -->
            <div class="pr-4 border-r border-gray-300">
                <form method="POST" action="{{ url('/your-action-url') }}"> <!-- Wrap inputs in a form -->
                    @csrf <!-- Add CSRF token for Laravel form submission -->
                    <div class="mb-4">
                        <h2 class="font-semibold text-lg mb-2">Absolute time range</h2>
                        <div class="space-y-2">
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700">From</label>
                                <input type="text" name="fromDate" id="fromDate" placeholder="now/y" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-400">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7.5V4.5a2.25 2.25 0 012.25-2.25h3a2.25 2.25 0 012.25 2.25v3M21 12.75v6a2.25 2.25 0 01-2.25 2.25h-13.5A2.25 2.25 0 013 18.75v-6M12 16.5V12m0 0l-3 3m3-3l-3-3m3 3l3-3" />
                                    </svg>
                                </div>
                            </div>
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700">To</label>
                                <input type="text" name="toDate" id="toDate" placeholder="now/y" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-400">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7.5V4.5a2.25 2.25 0 012.25-2.25h3a2.25 2.25 0 012.25 2.25v3M21 12.75v6a2.25 2.25 0 01-2.25 2.25h-13.5A2.25 2.25 0 013 18.75v-6M12 16.5V12m0 0l-3 3m3-3l-3-3m3 3l3-3" />
                                    </svg>
                                </div>
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
@endsection
