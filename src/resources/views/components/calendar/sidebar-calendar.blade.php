<div class="w-full pr-4">
    <x-calendar.sidebar-owner-user type="{{$type}}" timesheetableType="{{$timesheetableType}}" timesheetableId="{{$timesheetableId}}"/>
    <x-calendar.sidebar-filter type="{{$type}}" timesheetableType="{{$timesheetableType}}" timesheetableId="{{$timesheetableId}}" readOnly="{{$readOnly}}" />
    <div id="sidebar_tasklist_container" class="overflow-y-auto overflow-x-hidden h-[600px] mt-1 hidden">
        <x-calendar.sidebar-task 
            id="task_id_11111" 
            name="task_id" 
            tableName="pj_tasks" 
            typeToLoadListener="hr_timesheet_line" 
            readOnly="{{$readOnly}}" 
            />
        <div class="flex border rounded p-1 mt-1 hidden justify-center">
            <x-renderer.button click="toggleModal('modal-request-new-task')" badge="BETA" type="info">
                Request to add a new Task
            </x-renderer.button>            
            <x-calendar.modal-request-new-task modalId="modal-request-new-task" />
        </div>
    </div>
    <div id="sidebar_tasklist_container_warning" class="hidden1 mt-1">
        <div class="border text-sky-500 px-4 py-3 rounded relative" role="alert">
            {{-- <strong class="font-bold">Warning!</strong> --}}
            <span class="block sm:inline">Please select all filters to view tasks.</span>
        </div>
    </div>
</div>