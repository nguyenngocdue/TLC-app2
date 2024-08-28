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
        <form id="myFormBrowserTime" action="{{$routeFilter}}"  method="POST">
            @csrf
            <input type="hidden" name="form_type" value="updateBrowserTime">
            <input type="hidden" name="action" value="updateReport2">
            <input type="hidden" name="entity_type" value="{{$entityType}}">
            <input type="hidden" name="entity_type2" value="{{$reportType2}}">
            <input type="hidden" name="report_id" value="{{$rp->id}}">

            <select onchange="submitForm()"  id="timezone-select" name='time_zone'  placeholder="Type to search (country, city, abbreviation)">
                <option value="UTC" disabled>Coordinated Universal Time (UTC, GMT)</option>
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