<div class="w-full pr-4">
    <x-calendar.sidebar-mini-calendar />
    <x-calendar.sidebar-filter readOnly="{{$readOnly}}" type="{{$type}}" />
    <div class="overflow-y-auto overflow-x-hidden h-[600px]">
        <x-calendar.sidebar-task tableName="pj_tasks" name="task_id" typeToLoadListener="hr_timesheet_line" readOnly="{{$readOnly}}"/>  
    </div>
</div>