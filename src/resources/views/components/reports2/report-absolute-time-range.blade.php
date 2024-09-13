<div class="relative inline-block text-left z-10">
    <!-- Dropdown Button -->
    <button id="dropdownButton" class="relative flex items-center justify-between p-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 group">
        <i class="fa-solid fa-clock"></i>
        @php
            $timeTitle = $presetTitle == 'Time Range' ? $fromDate." <i class='fa-solid fa-arrow-right'></i> ".$toDate : $presetTitle;
        @endphp

        <span id="timeRangeShow" class="ml-2">{!!$timeTitle!!}</span>
        <i class="px-2 fa-solid fa-solid fa-chevron-down "></i>

        <!-- Tooltip -->
        @if($fromDate)
            <span class="absolute left-1/2 transform -translate-x-1/2 mt-36 hidden group-hover:block bg-white text-gray-700 p-2 rounded shadow-lg border border-gray-300 z-50 text-sm w-max">
                <span class="block">{{$fromDate}}</span>
                <span class="block">to</span>
                <span class="block">{{$toDate}}</span>
                <span class="block text-orange-600">{{$timeZone}}</span>

            </span>
        @endif
    </button>

    <!-- Dropdown Content (Initially Hidden) -->
    <div id="dropdownContent" class="hidden absolute z-10 mt-1 p-4 bg-white rounded-lg shadow-lg border border-gray-300 w-[500px] transform -translate-x-full left-16">
        <!-- Container for two-column layout -->
        <div class="grid grid-cols-2 border-b">
            <!-- Left column: From-To fields -->
            <div id="calendar" class="pr-4 border-r border-gray-300">
               @include('components.reports2.include-application-time-range')
            </div>
            <!-- Right column: Quick range selection dropdown -->
               @include('components.reports2.include-search-quick-time-range')
        </div>
         @include('components.reports2.include-time-setting')
    </div>
</div>

<script type="text/javascript">
    $('#timezone-select').select2({
        placeholder: 'Type to search time zone',
        allowClear: false,
    });

     $('#timezone-select').on('change', function () {
      var selectedText = $(this).find("option:selected").text();
      $('#browsertime-show').text(selectedText);
    });

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
        var calendar = document.getElementById('calendar');
        // Check if the clicked element is neither the button nor a descendant of the dropdown content
        if (!dropdownButton.contains(event.target) && !dropdownContent.contains(event.target) && !calendar) {
            dropdownContent.classList.add('hidden');
        }
    });

</script>