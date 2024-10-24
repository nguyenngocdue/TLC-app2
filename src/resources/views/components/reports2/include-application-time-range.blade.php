<form action="{{$routeFilter}}" method="POST" id="{{ $entityType }}">
    @csrf
    <div class="mb-4 w-3/4">
        <h2 class="font-semibold text-lg mb-2">Absolute Time Range</h2>
        <div class="space-y-2">
            <div class="relative">
                <input type="hidden" name='action' value="updateReport2">
                <input type="hidden" name='time_zone' value="{{$timeZone}}">
                <input type="hidden" name='entity_type' value="{{$entityType}}">
                <input type="hidden" name='entity_type2' value="{{$reportType2}}">
                <input type="hidden" name='report_id' value="{{$rp->id}}">
                <input type="hidden" name='form_type' value="updateAbsoluteTimeRange">
                <input type="hidden" name='preset_title' value="Absolute Time Range">
            </div>

            @if (!$rp->disable_from_date)
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700">From</label>
                    <input type="text" name="from_date" id="from_date" value="{{$fromDate}}" placeholder="Select a day" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
            @endif
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700">To</label>
                    <input type="text" name="to_date" value="{{$toDate}}" id="to_date" placeholder="Select a day" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
             {{-- Date Display Format --}}
            <div class="relative">
                <label class="block text-sm font-medium text-gray-700">Select date format to display</label>
                <input type="text" name="date_display_format" value="{{$dateDisplayFormat}}" id="date_display_format" placeholder="Select type of format" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
        </div>
        <div class="flex gap-2 pt-8 justify-evenly">
             <x-renderer.button htmlType="submit"  id="applyTimeRange" type="primary"><i class="fa-sharp fa-solid fa-check"></i> Apply Time</x-renderer.button>
             <x-renderer.button htmlType="submit" id="resetTimeRange" type="button" type="secondary"><i
                class="fa-sharp fa-solid fa-circle-xmark pr-1"></i>Reset</x-renderer.button>
        </div>

    </div>
</form>


{{-- Rest Time Range --}}
<script type="text/javascript">
    document.addEventListener('click', function(event) {
            var btbResetTimeRange = document.getElementById('resetTimeRange');
            if (btbResetTimeRange?.contains(event.target)) {
                 $('[id="' + "{{ $entityType }}" + '"]').append('<input type="hidden" name="form_type" value="resetAbsoluteTimeRange">')
            };
    });
</script>



 <!-- Add Flatpickr JS -->
{{-- <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> --}}
<script>
    // Initialize Flatpickr for the 'From' and 'To' date inputs
    var fromDatePicker = flatpickr("#from_date", {
        dateFormat: "Y-m-d H:i:S",
        allowInput: true,
        enableTime: true,
        onChange: function(selectedDates, dateStr, instance) {
            // Optional: handle the selected date
            if(!toDatePicker.input.value) {
                var endDate = new Date(selectedDates[0]); // Get the selected date
                endDate.setHours(23, 59, 59); // Set time to 23:59:59
                // Manually update the input value with the formatted date
                var formattedDate = instance.formatDate(endDate, "Y-m-d H:i:S");
                toDatePicker.setDate(formattedDate); // Update the date picker with the new time
            }
        }
    });

    var toDatePicker = flatpickr("#to_date", {
        dateFormat: "Y-m-d H:i:S",
        allowInput: true,
        enableTime: true,
        onChange: function(selectedDates, dateStr, instance) {
                var endDate = new Date(selectedDates[0]); // Get the selected date
                endDate.setHours(23, 59, 59); // Set time to 23:59:59
                // Manually update the input value with the formatted date
                var formattedDate = instance.formatDate(endDate, "Y-m-d H:i:S");
                instance.setDate(formattedDate); // Update the date picker with the new time

            if (!fromDatePicker.input.value) {
                // Set the from_date to the same day with time 00:00:00
                var startDate = new Date(selectedDates[0]); // Copy the selected date
                startDate.setHours(0, 0, 0); // Set time to 00:00:00
                var formattedStartDate = fromDatePicker.formatDate(startDate, "Y-m-d H:i:S");
                fromDatePicker.setDate(formattedStartDate); // Update the from_date picker with 00:00:00
            }
        }
    });
</script>