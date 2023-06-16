@php
    $projectId = $valueFiltersTask['project_id'];
    $subProjectId = $valueFiltersTask['sub_project_id'];
    $lodId = $valueFiltersTask['lod_id'];
    $disciplineId = $valueFiltersTask['discipline_id'] ?? $discipline;
@endphp
<x-renderer.card title="Filters">
    <div class="grid grid-cols-12 gap-y-2">
        <div class="col-span-12 2xl:col-span-5 text-right pr-2">Project</div>
        <div class="col-span-12 2xl:col-span-7">
            <x-calendar.sidebar-filter-project readOnly="{{$readOnly}}" tableName="projects" name="userSettings[project_id]" id="project_id" typeToLoadListener="hr_timesheet_line" selected="{{$projectId}}"/>
        </div>
        <div class="col-span-12 2xl:col-span-5 text-right pr-2">Sub Project</div>
        <div class="col-span-12 2xl:col-span-7">
            <x-calendar.sidebar-filter-sub-project readOnly="{{$readOnly}}" tableName="sub_projects" name="userSettings[sub_project_id]" id="sub_project_id" typeToLoadListener="hr_timesheet_line" selected="{{$subProjectId}}"/>
        </div>
        <div class="col-span-12 2xl:col-span-5 text-right pr-2">LOD</div> 
        <div class="col-span-12 2xl:col-span-7">
            <x-calendar.sidebar-filter-lod readOnly="{{$readOnly}}" tableName="terms" name="userSettings[lod_id]" id="lod_id" typeToLoadListener="hr_timesheet_line" selected="{{$lodId}}"/>
        </div>
        <div class="hidden col-span-12 2xl:col-span-5 text-right pr-2">Discipline</div> 
        <div class="hidden col-span-12 2xl:col-span-7">
            <x-calendar.sidebar-filter-discipline readOnly="{{$readOnly}}" tableName="user_disciplines" name="userSettings[discipline_id]" id="discipline_id" typeToLoadListener="hr_timesheet_line" selected="{{$discipline}}"/>
        </div>
    </div>
</x-renderer.card>