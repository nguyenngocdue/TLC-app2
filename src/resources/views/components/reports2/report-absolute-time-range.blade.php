<div class="relative inline-block text-left z-20 text-sm">
    <!-- Dropdown Button -->
    <button id="dropdownButton" class="relative flex items-center justify-between p-2 border border-gray-300 rounded-md shadow-lg bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 group">
        <i class="text-green-700 fa-solid fa-clock"></i>
        @php
            $presetTitle = ($presetTitle || $fromDate || $toDate) ? $presetTitle : '<strong>Time Range</strong>';
            switch(true){
                case $presetTitle != 'Absolute Time Range':
                    $timeTitle = "<strong>$presetTitle</strong>" ." (".$timeZone.")";
                    break;
                case $fromDate && $toDate:
                    if($rp->disable_from_date) {
                        $timeTitle = "<strong>$toDate</strong>"." (".$timeZone. ")";
                    } else {
                        $timeTitle = "<strong>$fromDate</strong>"." <i class='text-blue-600 fa-solid fa-arrow-right'></i> "."<strong>$toDate</strong>"." (".$timeZone. ")";
                    }
                    break;
                #case $rp->disable_from_date:
                #        $timeTitle = "<strong>$toDate</strong>"." (".$timeZone. ")";
                #    break;
                default:
                    $timeTitle = '<strong>Time Range</strong>';
                    break;
             }
             if ($rp->disable_from_date) {
                $timeTitle = "<strong>$toDate</strong>"." (".$timeZone. ")";
             }
        @endphp
        <span id="timeRangeShow" class="ml-2">{!!$timeTitle!!}</span>
        <i class="px-2 fa-solid fa-solid fa-chevron-down "></i>

        <!-- Tooltip -->
        @if($fromDate)
            <span class="absolute left-1/2 transform -translate-x-1/2 mt-36 hidden group-hover:block bg-white text-gray-700 p-2 rounded shadow-lg border border-gray-300 z-50 text-sm w-max">
                <span class="block">{{$fromDate}}</span>
                <span class="block">to</span>
                <span class="block">{{$toDate}}</span>
                <span class="block text-green-700 font-bold">{{$timeZone}}</span>

            </span>
        @endif
    </button>

    <!-- Dropdown Content (Initially Hidden) -->
    <div id="dropdownContent" style="width: 600px;" class="hidden absolute z-20 mt-1 p-4 bg-white rounded-lg shadow-lg border border-gray-300 transform -translate-x-full left-16">
        <!-- Container for two-column layout -->
        <div class="grid grid-cols-12 border-b">
            <!-- Left column: From-To fields -->
            <div id="calendar" class="col-span-7 pr-4 border-r border-gray-300">
               @include('components.reports2.include-application-time-range')
            </div>
            <!-- Right column: Quick range selection dropdown -->
            <div class="col-span-5">
                @include('components.reports2.include-search-quick-time-range')
            </div>
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

    document.addEventListener('click', function(event) {
            var dropdownContent = document.getElementById('dropdownContent');
            var dropdownButton = document.getElementById('dropdownButton');
            var calendarTop = document.getElementsByClassName('flatpickr-calendar animate open arrowTop');
            var calendarBot = document.getElementsByClassName('flatpickr-calendar animate open arrowBottom');
            var btnReset = document.getElementById('resetTimeRange');
            var btnApply = document.getElementById('applyTimeRange');
            // Check if the clicked element is not inside the dropdown content or the button
             if (!dropdownContent.contains(event.target) 
                    && !dropdownButton.contains(event.target) 
                    && !calendarTop[0]?.contains(event.target)
                    && !calendarBot[0]?.contains(event.target)
                    || btnReset?.contains(event.target)
                    || btnApply?.contains(event.target)
                 ) {
                // Hide the dropdown if clicked outside
                dropdownContent.classList.add('hidden');
            } 
           
        });

    document.getElementById('dropdownButton').addEventListener('click', function() {
        var dropdownContent = document.getElementById('dropdownContent');
        dropdownContent.classList.toggle('hidden');
    });

</script>

