<form action="{{$routeFilter}}" method="POST" id="quickRangeForm">
    @csrf
    <div class="pl-4 pb-2">
        <input type="text" placeholder="Search quick ranges" class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="quickRangeInput">
        <div class="mt-2 w-full bg-white border border-gray-300 rounded-md shadow-lg max-h-80 overflow-y-scroll z-10" id="quickRangeDropdown">
            <input type="hidden" name="form_type" value="updatePresetFilter">
            <input type="hidden" name="action" value="updateReport2">
            <input type="hidden" name="entity_type" value="{{$entityType}}">
            <input type="hidden" name="entity_type2" value="{{$reportType2}}">
            <input type="hidden" name="report_id" value="{{$rp->id}}">
            <input type="hidden" name="time_zone" value="{{$timeZone}}">
            <input type="hidden" id="preset_title" name="preset_title" value="Time Range">
            <ul class="py-1 text-gray-700" id="quickRangeList">
                <li name="preset_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                    data-value="{{ $presets['today']['from_date'] }} / {{ $presets['today']['to_date'] }}">
                    Today
                </li>
                <li name="preset_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                    data-value="{{ $presets['today_so_far']['from_date'] }} / {{ $presets['today_so_far']['to_date'] }}">
                    Today so far
                </li>
               <li name="preset_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                    data-value="{{ $presets['this_week']['from_date'] }} / {{ $presets['this_week']['to_date'] }}">
                    This week
                </li>
                <li name="preset_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                    data-value="{{ $presets['this_week_so_far']['from_date'] }} / {{ $presets['this_week_so_far']['to_date'] }}">
                    This week so far
                </li>

                <li name="preset_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                    data-value="{{ $presets['this_month']['from_date'] }} / {{ $presets['this_month']['to_date'] }}">
                    This month
                </li>

                <li name="preset_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                    data-value="{{ $presets['this_month_so_far']['from_date'] }} / {{ $presets['this_month_so_far']['to_date'] }}">
                    This month so far
                </li>

                <li name="preset_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                    data-value="{{ $presets['this_year']['from_date'] }} / {{ $presets['this_year']['to_date'] }}">
                    This year
                </li>

                <li name="preset_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                    data-value="{{ $presets['this_year_so_far']['from_date'] }} / {{ $presets['this_year_so_far']['to_date'] }}">
                    This year so far
                </li>

                <li name="preset_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                    data-value="{{ $presets['first_half_year']['from_date'] }} / {{ $presets['first_half_year']['to_date'] }}">
                    1st half of the curent year
                </li>

                <li name="preset_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                    data-value="{{ $presets['second_half_year']['from_date'] }} / {{ $presets['second_half_year']['to_date'] }}">
                    2nd half of the curent year
                </li>



                <li name="preset_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                    data-value="{{ $presets['this_quarter']['from_date'] }} / {{ $presets['this_quarter']['to_date'] }}">
                    This quater
                </li>
                 <li name="preset_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                    data-value="{{ $presets['this_quarter_so_far']['from_date'] }} / {{ $presets['this_quarter_so_far']['to_date'] }}">
                    This quater so far
                </li>
                   <li name="preset_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                    data-value="{{ $presets['first_quarter']['from_date'] }} / {{ $presets['first_quarter']['to_date'] }}">
                    First quarter
                </li>
                   <li name="preset_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                    data-value="{{ $presets['second_quarter']['from_date'] }} / {{ $presets['second_quarter']['to_date'] }}">
                    Second quarter
                </li>
                   <li name="preset_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                    data-value="{{ $presets['third_quarter']['from_date'] }} / {{ $presets['third_quarter']['to_date'] }}">
                    Third quarter
                </li>
                   <li name="preset_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                    data-value="{{ $presets['fourth_quarter']['from_date'] }} / {{ $presets['fourth_quarter']['to_date'] }}">
                    Fourth quarter
                </li>
                {{-- Time Range Last--}}
                <li name="preset_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                    data-value="{{ $presets['last_5_minutes']['from_date'] }} / {{ $presets['last_5_minutes']['to_date'] }}">
                    Last 5 minutes
                </li>
                <li name="preset_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                    data-value="{{ $presets['last_15_minutes']['from_date'] }} / {{ $presets['last_15_minutes']['to_date'] }}">
                    Last 15 minutes
                </li>
                <li name="preset_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                    data-value="{{ $presets['last_30_minutes']['from_date'] }} / {{ $presets['last_30_minutes']['to_date'] }}">
                    Last 30 minutes
                </li>
                <li name="preset_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                    data-value="{{ $presets['last_1_hour']['from_date'] }} / {{ $presets['last_1_hour']['to_date'] }}">
                    Last 1 hour
                </li>
                <li name="preset_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                    data-value="{{ $presets['last_3_hours']['from_date'] }} / {{ $presets['last_3_hours']['to_date'] }}">
                    Last 3 hours
                </li>
                <li name="preset_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                    data-value="{{ $presets['last_6_hours']['from_date'] }} / {{ $presets['last_6_hours']['to_date'] }}">
                    Last 6 hours
                </li>
                <li name="preset_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                    data-value="{{ $presets['last_12_hours']['from_date'] }} / {{ $presets['last_12_hours']['to_date'] }}">
                    Last 12 hours
                </li>
                <li name="preset_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                    data-value="{{ $presets['yesterday']['from_date'] }} / {{ $presets['yesterday']['to_date'] }}">
                    Yesterday
                </li>
                <li name="preset_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                    data-value="{{ $presets['last_2_days']['from_date'] }} / {{ $presets['last_2_days']['to_date'] }}">
                    Last 2 days
                </li>
                <li name="preset_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                    data-value="{{ $presets['last_week']['from_date'] }} / {{ $presets['last_week']['to_date'] }}">
                    Last week
                </li>
                <li name="preset_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                    data-value="{{ $presets['last_month']['from_date'] }} / {{ $presets['last_month']['to_date'] }}">
                    Last month
                </li>
                <li name="preset_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                    data-value="{{ $presets['last_year']['from_date'] }} / {{ $presets['last_year']['to_date'] }}">
                    Last year
                </li>
                
                <li name="preset_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                    data-value="{{ $presets['last_2_years']['from_date'] }} / {{ $presets['last_2_years']['to_date'] }}">
                    Last two years
                </li>

            </ul>
        </div>
    </div>
</form>

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
            // Get the name attribute from the clicked item (e.g., "preset_1", "pro_set_2")
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
            document.getElementById('preset_title').value = this.textContent.trim();
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