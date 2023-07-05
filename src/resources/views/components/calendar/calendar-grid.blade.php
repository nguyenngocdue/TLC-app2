<div class="grid grid-cols-6">
      <div class="col-span-1 {{$hasRenderSidebar ? '' : 'hidden'}}">
          <x-calendar.sidebar-calendar readOnly="{{$readOnly}}" type="{{$type}}" timesheetableType="{{$timesheetableType}}" timesheetableId="{{$timesheetableId}}"/>
      </div>
      <div class="{{$hasRenderSidebar ? 'col-span-5' : 'col-span-6'}}">
        <x-calendar.full-calendar timesheetableType="{{$timesheetableType}}" timesheetableId="{{$timesheetableId}}" apiUrl="{{$apiUrl}}" readOnly="{{$readOnly}}" :arrHidden="$arrHidden"></x-calendar.full-calendar>
      </div>
  </div>