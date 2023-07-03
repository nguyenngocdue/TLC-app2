<div class="bg-white w-full my-2 p-1 rounded grid grid-cols-12 gap-2">
    <div class="col-span-3">
        Project:
        <x-calendar.sidebar-filter-project 
            tableName="projects" 
            name="userSettings[project_id]" 
            id="project_id" 
            typeToLoadListener="qaqc_wir" 
            selected="{{$viewportParams['project_id']}}"
            />
    </div>
    <div class="col-span-3">
        Sub-Project:
        <x-calendar.sidebar-filter-sub-project 
            tableName="sub_projects" 
            name="userSettings[sub_project_id]" 
            id="sub_project_id" 
            typeToLoadListener="qaqc_wir" 
            selected="{{$viewportParams['sub_project_id']}}"
            />
    </div>
    <div class="col-span-3">
        Production Routing
        <x-calendar.sidebar-filter-prod-routing 
            tableName="prod_routings" 
            name="userSettings[prod_routing_id]" 
            id="prod_routing_id" 
            typeToLoadListener="qaqc_wir" 
            selected="{{$viewportParams['prod_routing_id']}}"
            />
    </div>
</div>