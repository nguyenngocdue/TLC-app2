@php
    $projectId = $valueFiltersTask['project_id'] ?? 1;
    $subProjectId = $valueFiltersTask['sub_project_id'] ?? 1;
    $lodId = $valueFiltersTask['lod_id'] ?? 219; //HOF:219
@endphp
<x-renderer.card title="Task Filter">
    <div class="grid grid-cols-12 gap-y-2">
        <div class="col-span-12 2xl:col-span-5 flex items-center justify-end pr-2 text-right">Project</div>
        <div class="col-span-12 2xl:col-span-7">
            <x-calendar.SidebarFilterProject readOnly="{{$readOnly}}" tableName="projects" name="userSettings[project_id]" id="project_id_11111" typeToLoadListener="hr_timesheet_line" selected="{{$projectId}}"/>
        </div>
        <div class="col-span-12 2xl:col-span-5 flex items-center justify-end pr-2 text-right">Sub Project</div>
        <div class="col-span-12 2xl:col-span-7">
            <x-calendar.SidebarFilterSubProject readOnly="{{$readOnly}}" tableName="sub_projects" name="userSettings[sub_project_id]" id="sub_project_id_11111" typeToLoadListener="hr_timesheet_line" selected="{{$subProjectId}}"/>
        </div>
    </div>
    <div class="grid grid-cols-12 gap-y-2">
        <div class="hidden col-span-12 2xl:col-span-5 flex items-center justify-end pr-2 text-right">Discipline</div> 
        <div class="hidden col-span-12 2xl:col-span-7">
            <x-calendar.SidebarFilterDiscipline readOnly="true" tableName="user_disciplines" name="userSettings[discipline_id]" id="discipline_id_11111" typeToLoadListener="hr_timesheet_line" selected="{{$selectedUserDisciplineId}}"/>
        </div>

        <div class="col-span-12 2xl:col-span-5  flex items-center justify-end pr-2 text-right">LOD</div> 
        <div class="col-span-12 2xl:col-span-7">
            <x-calendar.SidebarFilterLod readOnly="{{$readOnly}}" tableName="terms" name="userSettings[lod_id]" id="lod_id_11111" typeToLoadListener="hr_timesheet_line" selected="{{$lodId}}"/>
        </div>
    </div>
    {{-- <div class="overflow-y-auto overflow-x-hidden h-[600px] mt-1">
        <x-calendar.sidebar-task tableName="pj_tasks" name="task_id" id="task_id_11111" typeToLoadListener="hr_timesheet_line" readOnly="{{$readOnly}}" />
    </div> --}}
</x-renderer.card>