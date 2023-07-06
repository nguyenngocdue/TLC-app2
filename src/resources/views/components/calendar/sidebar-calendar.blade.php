<div class="w-full pr-4">
    <x-calendar.sidebar-owner-user type="{{$type}}" timesheetableType="{{$timesheetableType}}" timesheetableId="{{$timesheetableId}}"/>
    <x-calendar.sidebar-filter readOnly="{{$readOnly}}" type="{{$type}}" />
    <div class="overflow-y-auto overflow-x-hidden h-[600px]">
        {{-- <x-calendar.sidebar-task tableName="pj_tasks" name="task_id" id="task_id_11111" typeToLoadListener="hr_timesheet_line" readOnly="{{$readOnly}}" /> --}}
    </div>
</div>