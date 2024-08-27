<div class="relative inline-block text-left z-50">
    <!-- Dropdown Button -->
    <button id="dropdownButton" class="relative flex items-center justify-between p-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 group">
        <i class="fa-solid fa-clock"></i>
        <span id="timeRangeShow" class="ml-2">{{$proSetTitle}}</span>
        <i class="px-2 fa-solid fa-arrow-down"></i>

        <!-- Tooltip -->
        <span class="absolute left-1/2 transform -translate-x-1/2 mt-36 hidden group-hover:block bg-white text-gray-700 p-2 rounded shadow-lg border border-gray-300 z-50 text-sm w-max">
            <span class="block">{{$fromDate}}</span>
            <span class="block">to</span>
            <span class="block">{{$toDate}}</span>
            <span class="block text-orange-600">Local browser time</span>
        </span>
    </button>




    <!-- Dropdown Content (Initially Hidden) -->
    <div id="dropdownContent" class="hidden absolute z-10 mt-2 p-4 bg-white rounded-lg shadow-lg border border-gray-300 w-[500px] transform -translate-x-full left-16">
        <!-- Container for two-column layout -->
        <div class="grid grid-cols-2 border-b">
            <!-- Left column: From-To fields -->
            <div class="pr-4 border-r border-gray-300">
                <form action="{{$routeFilter}}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <h2 class="font-semibold text-lg mb-2">Absolute time range</h2>
                        <div class="space-y-2">
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700">From</label>
                                <input type="hidden" name='action' value="updateReport2">
                                <input type="hidden" name='entity_type' value="{{$entityType}}">
                                <input type="hidden" name='entity_type2' value="{{$reportType2}}">
                                <input type="hidden" name='report_id' value="{{$rp->id}}">
                                <input type="hidden" name='form_type' value="absolute_time_range">
                                <input type="text" name="from_date" id="from_date" value="{{$fromDate}}" placeholder="now/y" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700">To</label>
                                <input type="text" name="to_date" value="{{$toDate}}" id="to_date" placeholder="now/y" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                        </div>
                        <button type="submit" class="mt-4 w-full bg-blue-600 text-white py-2 px-4 rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Apply time range</button>
                    </div>
                </form>
            </div>

            <!-- Right column: Quick range selection dropdown -->
            <form action="{{$routeFilter}}" method="POST" id="quickRangeForm">
                @csrf
                <div class="pl-4 pb-2">
                    <input type="text" placeholder="Search quick ranges" class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="quickRangeInput">
                    <div class="mt-2 w-full bg-white border border-gray-300 rounded-md shadow-lg max-h-80 overflow-y-scroll z-10" id="quickRangeDropdown">
                        <input type="hidden" name="form_type" value="updateProSetFilter">
                        <input type="hidden" name="action" value="updateReport2">
                        <input type="hidden" name="entity_type" value="{{$entityType}}">
                        <input type="hidden" name="entity_type2" value="{{$reportType2}}">
                        <input type="hidden" name="report_id" value="{{$rp->id}}">
                        <input type="hidden" id="pro_set_title" name="pro_set_title" value="Time Range">
                       <ul class="py-1 text-gray-700" id="quickRangeList">
                            <li name="pro_set_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                                data-value="{{ $proSets['today']['from_date'] }} / {{ $proSets['today']['to_date'] }}">
                                Today
                            </li>
                            <li name="pro_set_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                                data-value="{{ $proSets['yesterday']['from_date'] }} / {{ $proSets['yesterday']['to_date'] }}">
                                Yesterday
                            </li>
                            <li name="pro_set_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                                data-value="{{ $proSets['last_2_days']['from_date'] }} / {{ $proSets['last_2_days']['to_date'] }}">
                                Last 2 days
                            </li>
                            <li name="pro_set_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                                data-value="{{ $proSets['last_week']['from_date'] }} / {{ $proSets['last_week']['to_date'] }}">
                                Last week
                            </li>
                            <li name="pro_set_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                                data-value="{{ $proSets['last_month']['from_date'] }} / {{ $proSets['last_month']['to_date'] }}">
                                Last month
                            </li>
                            <li name="pro_set_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                                data-value="{{ $proSets['last_year']['from_date'] }} / {{ $proSets['last_year']['to_date'] }}">
                                Last year
                            </li>
                            <!-- New time frames -->
                            <li name="pro_set_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                                data-value="{{ $proSets['last_5_minutes']['from_date'] }} / {{ $proSets['last_5_minutes']['to_date'] }}">
                                Last 5 minutes
                            </li>
                            <li name="pro_set_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                                data-value="{{ $proSets['last_15_minutes']['from_date'] }} / {{ $proSets['last_15_minutes']['to_date'] }}">
                                Last 15 minutes
                            </li>
                            <li name="pro_set_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                                data-value="{{ $proSets['last_30_minutes']['from_date'] }} / {{ $proSets['last_30_minutes']['to_date'] }}">
                                Last 30 minutes
                            </li>
                            <li name="pro_set_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                                data-value="{{ $proSets['last_1_hour']['from_date'] }} / {{ $proSets['last_1_hour']['to_date'] }}">
                                Last 1 hour
                            </li>
                            <li name="pro_set_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                                data-value="{{ $proSets['last_3_hours']['from_date'] }} / {{ $proSets['last_3_hours']['to_date'] }}">
                                Last 3 hours
                            </li>
                            <li name="pro_set_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                                data-value="{{ $proSets['last_6_hours']['from_date'] }} / {{ $proSets['last_6_hours']['to_date'] }}">
                                Last 6 hours
                            </li>
                            <li name="pro_set_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                                data-value="{{ $proSets['last_12_hours']['from_date'] }} / {{ $proSets['last_12_hours']['to_date'] }}">
                                Last 12 hours
                            </li>
                        </ul>
                    </div>
                </div>
            </form>
        </div>
        <div class="p-4">
            <!-- Header Section -->
            <div class="grid grid-cols-2">
                <span class="text-sm font-semibold" id="browsertime-show">{{$browserTime}}</span>
                <div class="flex items-center justify-end">
                    <button id="change-time-settings" class="text-sm text-blue-500 border border-gray-300 rounded-md px-2 py-1 hover:bg-gray-100">Change time settings</button>
                </div>
            </div>

            <!-- Time Zones List -->
            <div id="timezone-container" style="display: none;">
                <!-- Tabs Section -->
                <div class="border-b border-gray-300 mb-4">
                    <ul class="flex space-x-4 text-sm font-medium">
                        <li class="pb-2 border-b-2 border-blue-500 text-blue-600 cursor-pointer">Time zone</li>
                    </ul>
                </div>


                <form action="{{$routeFilter}}"  method="POST">
                    @csrf
                    <input type="hidden" name="form_type" value="updateBrowserTime">
                    <input type="hidden" name="action" value="updateReport2">
                    <input type="hidden" name="entity_type" value="{{$entityType}}">
                    <input type="hidden" name="entity_type2" value="{{$reportType2}}">
                    <input type="hidden" name="report_id" value="{{$rp->id}}">

                    <select onchange="submitForm()"  id="timezone-select" name='browser_time'  placeholder="Type to search (country, city, abbreviation)">
                        <option value="UTC">Coordinated Universal Time (UTC, GMT)</option>
                        <!-- America -->
                        @foreach($timezoneData as $continent  => $countries)
                            <optgroup label="{{$continent}}">
                                @foreach($countries as $country) {
                                    <option value="{{$country}}">{{$country}}</option>
                                }
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
    $('#timezone-select').select2({
        placeholder: 'Type to search (country, city, arrbeviation)',
        allowClear: false,
    });

//     $('#timezone-select').on('change', function () {
//      var selectedText = $(this).find("option:selected").text();
//      $('#browsertime-show').text(selectedText);
//    });

     // Show the timezone dropdown when the button is clicked
    $('#change-time-settings').on('click', function () {
        $('#timezone-container').toggle();  // Toggle visibility of the dropdown
    });

</script>


<!-- JavaScript to toggle dropdown visibility -->
<script>
    document.getElementById('dropdownButton').addEventListener('click', function() {
        var dropdownContent = document.getElementById('dropdownContent');
        dropdownContent.classList.toggle('hidden');
    });

     document.addEventListener('click', function(event) {
        var dropdownContent = document.getElementById('dropdownContent');
        var dropdownButton = document.getElementById('dropdownButton');
        // Check if the clicked element is neither the button nor a descendant of the dropdown content
        if (!dropdownButton.contains(event.target) && !dropdownContent.contains(event.target)) {
            dropdownContent.classList.add('hidden');
        }
    });

</script>

    <!-- Add Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Initialize Flatpickr for the 'From' and 'To' date inputs
        var fromDatePicker =  flatpickr("#from_date", {
            dateFormat: "Y-m-d H:i:S",
            allowInput: true,
            onChange: function(selectedDates, dateStr, instance) {
                // Optional: handle the selected date
            }
        });

        var toDatePicker = flatpickr("#to_date", {
            dateFormat: "Y-m-d H:i:S",
            allowInput: true,
            onChange: function(selectedDates, dateStr, instance) {
                // Optional: handle the selected date
            }
        });
    </script>  
{{-- Search quick ranges --}}
<script>
    document.getElementById('quickRangeInput').addEventListener('input', function() {
    const filter = this.value.toLowerCase();
    const listItems = document.querySelectorAll('#quickRangeList li');
    
    listItems.forEach(function(item) {
        const text = item.textContent.toLowerCase();
        if (text.includes(filter)) {
            item.style.display = ''; // Show the item if it matches the input
        } else {
            item.style.display = 'none'; // Hide the item if it doesn't match
        }
    });
});
</script>

<script>
    document.querySelectorAll('#quickRangeList li').forEach(item => {
        item.addEventListener('click', function() {
            // Get the name attribute from the clicked item (e.g., "pro_set_1", "pro_set_2")
            let name = this.getAttribute('name');
            
            // Create a hidden input with the corresponding name and set its value
            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = name;
            input.value = this.getAttribute('data-value');

            // Remove any existing input with the same name to avoid duplicates
            let existingInput = document.querySelector(`input[name="${name}"]`);
            if (existingInput) {
                existingInput.remove();
            }

            // Append the hidden input to the form
            document.getElementById('quickRangeForm').appendChild(input);

            // Optionally display the selected text in the input field
            document.getElementById('pro_set_title').value = this.textContent.trim();

            // Submit the form
            document.getElementById('quickRangeForm').submit();

        });
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
