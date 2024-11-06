<style>
/* Hide scrollbar for Chrome, Safari, and Edge */
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}

/* Hide scrollbar for Firefox */
.scrollbar-hide {
    scrollbar-width: none; /* Firefox */
}

/* Show scrollbar when scrolling */
.scrollbar-show::-webkit-scrollbar {
    width: 8px;
}

.scrollbar-show::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, 0.3);
    border-radius: 4px;
}

</style>

<form action="{{$routeFilter}}" method="POST" id="quickRangeForm">
    @csrf
    <div class="pl-4 pb-2">
        <input type="text" placeholder="Search quick ranges" class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="quickRangeInput">
        <div class="mt-2 w-full bg-white max-h-80 overflow-y-scroll z-10 scrollbar-hide" id="quickRangeDropdown">
            <input type="hidden" name="form_type" value="updatePresetFilter">
            <input type="hidden" name="action" value="updateReport2">
            <input type="hidden" name="entity_type" value="{{$entityType}}">
            <input type="hidden" name="entity_type2" value="{{$reportType2}}">
            <input type="hidden" name="report_id" value="{{$rp->id}}">
            <input type="hidden" name="time_zone" value="{{$timeZone}}">
            <input type="hidden" id="preset_title" name="preset_title" value="Time Range">
            <ul class="py-1 text-gray-700" id="quickRangeList">
                @foreach($presets as $key => $preset)
                    <li name="preset_1" id="{{$key}}" class="px-2 py-1 hover:bg-gray-100 cursor-pointer" 
                        data-value="{{ $preset['from_date'] }} / {{ $preset['to_date'] }}">
                        {{App\Utils\Support\Report::makeTitle($key)}}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</form>

{{-- Search quick ranges --}}
<script>

    const quickRangeDropdown = document.getElementById('quickRangeDropdown');
    quickRangeDropdown.addEventListener('scroll', function() {
        if (!quickRangeDropdown.classList.contains('scrollbar-show')) {
            quickRangeDropdown.classList.add('scrollbar-show');
            quickRangeDropdown.classList.remove('scrollbar-hide');
        }
    });

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
            var dropdownContent = document.getElementById('dropdownContent');
            dropdownContent.classList.add('hidden');

        });
    });
</script>
