<div class="grid grid-cols-12">
      <div class="col-span-3 {{$hasRenderSidebar ? '' : 'hidden'}}">
          <x-calendar.sidebar-calendar readOnly="{{$readOnly}}" type="{{$type}}" timesheetableType="{{$timesheetableType}}" timesheetableId="{{$timesheetableId}}"/>
      </div>
      <div class="{{$hasRenderSidebar ? 'col-span-9' : 'col-span-12'}}">
        <x-calendar.navigation-calendar 
            timesheetId="{{$timesheetableId}}" 
            :sheetOwner="$sheetOwner" 
            hidden="{{$hidden}}"
            />
        <x-calendar.full-calendar 
            timesheetableType="{{$timesheetableType}}" 
            timesheetableId="{{$timesheetableId}}" 
            apiUrl="{{$apiUrl}}" 
            hiddenCalendarHeader="{{$hidden}}"
            readOnly="{{$readOnly}}" 
            :arrHidden="$arrHidden"
            :sheetOwner="$sheetOwner" 
            >
        </x-calendar.full-calendar>
      </div>
  </div>