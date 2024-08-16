@php
    $projectId = $valueFiltersTask['project_id'] ?? 1;
    $subProjectId = $valueFiltersTask['sub_project_id'] ?? 1;
    $lodId = $valueFiltersTask['lod_id'] ?? 219; //HOF:219
    $workModeId = $valueFiltersTask['work_mode_id'] ?? 2;
@endphp
<x-renderer.card title="Task Filter">
    <div class="grid grid-cols-12 gap-y-2">

        <div class="hidden col-span-12 2xl:col-span-5 flex1 items-center justify-end pr-2 text-right">Discipline <i class='text-red-400' title='required'>*</i></div> 
        <div class="hidden col-span-12 2xl:col-span-7">
            <x-calendar.SidebarFilterDiscipline readOnly="true" tableName="user_disciplines" name="userSettings[discipline_id]" id="discipline_id_11111" typeToLoadListener="hr_timesheet_officer_line" selected="{{$selectedUserDisciplineId}}"/>
        </div>
        
        <div class="col-span-12 2xl:col-span-5 flex items-center justify-end pr-2 text-right">Project <i class='text-red-400' title='required'>*</i></div>
        <div class="col-span-12 2xl:col-span-7">
            <x-calendar.SidebarFilterProject readOnly="{{$readOnly}}" tableName="projects" name="userSettings[project_id]" id="project_id_11111" typeToLoadListener="hr_timesheet_officer_line" selected="{{$projectId}}"/>
        </div>

        <div class="col-span-12 2xl:col-span-5 flex items-center justify-end pr-2 text-right">Sub Project <i class='text-red-400' title='required'>*</i></div>
        <div class="col-span-12 2xl:col-span-7">
            <x-calendar.SidebarFilterSubProject readOnly="{{$readOnly}}" tableName="sub_projects" name="userSettings[sub_project_id]" id="sub_project_id_11111" typeToLoadListener="hr_timesheet_officer_line" selected="{{$subProjectId}}"/>
        </div>
   
        <div class="col-span-12 2xl:col-span-5  flex items-center justify-end pr-2 text-right">Phase <i class='text-red-400' title='required'>*</i></div> 
        <div class="col-span-12 2xl:col-span-7">
            <x-calendar.SidebarFilterLod readOnly="{{$readOnly}}" tableName="pj_task_phases" name="userSettings[lod_id]" id="lod_id_11111" typeToLoadListener="hr_timesheet_officer_line" selected="{{$lodId}}"/>
        </div>
       
        <div class="col-span-12 2xl:col-span-5  flex items-center justify-end pr-2 text-right">Work Mode <i class='text-red-400' title='required'>*</i></div> 
        <div class="col-span-12 2xl:col-span-7">
            <x-calendar.SidebarFilterWorkMode readOnly="{{$readOnly}}" tableName="work_modes" name="userSettings[work_mode_id]" id="work_mode_id_11111" typeToLoadListener="hr_timesheet_officer_line" selected="{{$workModeId}}"/>
        </div>
    </div>
</x-renderer.card>

<script src="{{ asset('js/components/FullCalendarEdit-ProjectPhase-20240816.js') }}"></script>