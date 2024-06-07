@php
    $projectId = $valueFiltersTask['project_id'] ?? 1;
    $subProjectId = $valueFiltersTask['sub_project_id'] ?? 1;
    $lodId = $valueFiltersTask['lod_id'] ?? 219; //HOF:219
@endphp
<x-renderer.card title="Task Filter">
    <div class="grid grid-cols-12 gap-y-2">
        {{-- @php
            $hidden = App\Utils\Support\CurrentUser::isAdmin() ? '' : 'hidden';
        @endphp --}}
        <div class="hidden col-span-12 2xl:col-span-5 flex1 items-center justify-end pr-2 text-right">Discipline</div> 
        <div class="hidden col-span-12 2xl:col-span-7">
            <x-calendar.SidebarFilterDiscipline readOnly="true" tableName="user_disciplines" name="userSettings[discipline_id]" id="discipline_id_11111" typeToLoadListener="hr_timesheet_line" selected="{{$selectedUserDisciplineId}}"/>
        </div>
        
        <div class="col-span-12 2xl:col-span-5 flex items-center justify-end pr-2 text-right">Project</div>
        <div class="col-span-12 2xl:col-span-7">
            <x-calendar.SidebarFilterProject readOnly="{{$readOnly}}" tableName="projects" name="userSettings[project_id]" id="project_id_11111" typeToLoadListener="hr_timesheet_line" selected="{{$projectId}}"/>
        </div>

        <div class="col-span-12 2xl:col-span-5 flex items-center justify-end pr-2 text-right">Sub Project</div>
        <div class="col-span-12 2xl:col-span-7">
            <x-calendar.SidebarFilterSubProject readOnly="{{$readOnly}}" tableName="sub_projects" name="userSettings[sub_project_id]" id="sub_project_id_11111" typeToLoadListener="hr_timesheet_line" selected="{{$subProjectId}}"/>
        </div>
   
        <div class="col-span-12 2xl:col-span-5  flex items-center justify-end pr-2 text-right">Phase</div> 
        <div class="col-span-12 2xl:col-span-7">
            <x-calendar.SidebarFilterLod readOnly="{{$readOnly}}" tableName="pj_task_phases" name="userSettings[lod_id]" id="lod_id_11111" typeToLoadListener="hr_timesheet_line" selected="{{$lodId}}"/>
        </div>
    </div>
</x-renderer.card>