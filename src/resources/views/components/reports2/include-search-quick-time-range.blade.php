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
                    <!-- New time frames -->
                <li name="pro_set_1" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" 
                    data-value="{{ $proSets['today']['from_date'] }} / {{ $proSets['today']['to_date'] }}">
                    Today
                </li>
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