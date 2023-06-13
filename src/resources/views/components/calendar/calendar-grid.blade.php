<div class="grid grid-cols-6">
    <div class="col-span-1">
      <x-calendar.sidebar-calendar/>
      </div>
      <div class="col-span-5">
        <x-calendar.full-calendar timesheetableType="{{$timesheetableType}}" timesheetableId="{{$timesheetableId}}" apiUrl="{{$apiUrl}}"></x-calendar.full-calendar>
      </div>
  </div>