@php
    $projectId = $valueFiltersTask['project_id'] ?? 1;
    $subProjectId = $valueFiltersTask['sub_project_id'] ?? 1;
    $lodId = $valueFiltersTask['lod_id'] ?? 219; //HOF:219
    $disciplineId = $valueFiltersTask['discipline_id'] ?? $discipline;
@endphp
<x-renderer.card title="Filters">
    <div class="grid grid-cols-12 gap-y-2">
        <div class="col-span-12 2xl:col-span-5 flex items-center justify-end pr-2 text-right">Project</div>
        <div class="col-span-12 2xl:col-span-7">
            <x-calendar.sidebar-filter-project readOnly="{{$readOnly}}" tableName="projects" name="userSettings[project_id]" id="project_id_11111" typeToLoadListener="hr_timesheet_line" selected="{{$projectId}}"/>
        </div>
        <div class="col-span-12 2xl:col-span-5 flex items-center justify-end pr-2 text-right">Sub Project</div>
        <div class="col-span-12 2xl:col-span-7">
            <x-calendar.sidebar-filter-sub-project readOnly="{{$readOnly}}" tableName="sub_projects" name="userSettings[sub_project_id]" id="sub_project_id_11111" typeToLoadListener="hr_timesheet_line" selected="{{$subProjectId}}"/>
        </div>
        <div class="col-span-12 2xl:col-span-5  flex items-center justify-end pr-2 text-right">LOD</div> 
        <div class="col-span-12 2xl:col-span-7">
            <x-calendar.sidebar-filter-lod readOnly="{{$readOnly}}" tableName="terms" name="userSettings[lod_id]" id="lod_id_11111" typeToLoadListener="hr_timesheet_line" selected="{{$lodId}}"/>
        </div>
        <div class="hidden col-span-12 2xl:col-span-5 flex1 items-center justify-end pr-2 text-right">Discipline</div> 
        <div class="hidden col-span-12 2xl:col-span-7">
            <x-calendar.sidebar-filter-discipline readOnly="{{$readOnly}}" tableName="user_disciplines" name="userSettings[discipline_id]" id="discipline_id_11111" typeToLoadListener="hr_timesheet_line" selected="{{$discipline}}"/>
        </div>
    </div>
</x-renderer.card>