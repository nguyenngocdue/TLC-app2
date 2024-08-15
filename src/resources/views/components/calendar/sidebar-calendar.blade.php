<div class="w-full pr-4">
    <x-calendar.sidebar-owner-user type="{{$type}}" timesheetableType="{{$timesheetableType}}" timesheetableId="{{$timesheetableId}}"/>
    <x-calendar.sidebar-filter type="{{$type}}" timesheetableType="{{$timesheetableType}}" timesheetableId="{{$timesheetableId}}" readOnly="{{$readOnly}}" />
    <div id="sidebar_tasklist_container" class="overflow-y-auto overflow-x-hidden h-[600px]">
        <x-calendar.sidebar-task 
            id="task_id_11111" 
            name="task_id" 
            tableName="pj_tasks" 
            typeToLoadListener="hr_timesheet_line" 
            readOnly="{{$readOnly}}" 
            />
    </div>
    <div id="sidebar_tasklist_container_warning" class="hidden1">
        <div class="border text-yellow-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Warning!</strong>
            <span class="block sm:inline">Please select all filters to view tasks.</span>
        </div>
    </div>
</div>