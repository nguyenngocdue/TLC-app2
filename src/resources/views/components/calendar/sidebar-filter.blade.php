<x-renderer.card title="Filters">
    <div class="grid grid-cols-12 gap-y-2">
        <div class="col-span-12 2xl:col-span-5 text-right pr-2">Project</div>
        <div class="col-span-12 2xl:col-span-7">
            <x-calendar.sidebar-filter-project tableName="projects" name="project_id" typeToLoadListener="hr_timesheet_line" selected="5"/>
        </div>
        <div class="col-span-12 2xl:col-span-5 text-right pr-2">Sub Project</div>
        <div class="col-span-12 2xl:col-span-7">
            <x-calendar.sidebar-filter-sub-project tableName="sub_projects" name="sub_project_id" typeToLoadListener="hr_timesheet_line" selected="82"/>
        </div>
        <div class="col-span-12 2xl:col-span-5 text-right pr-2">LOD</div> 
        <div class="col-span-12 2xl:col-span-7">
            <x-calendar.sidebar-filter-lod tableName="terms" name="lod_id" typeToLoadListener="hr_timesheet_line" selected="228"/>
        </div>
        <div class="hidden col-span-12 2xl:col-span-5 text-right pr-2">Discipline</div> 
        <div class="hidden col-span-12 2xl:col-span-7">
            <x-calendar.sidebar-filter-discipline tableName="user_disciplines" name="discipline_id" typeToLoadListener="hr_timesheet_line" selected="{{$discipline}}"/>
        </div>
    </div>
</x-renderer.card>