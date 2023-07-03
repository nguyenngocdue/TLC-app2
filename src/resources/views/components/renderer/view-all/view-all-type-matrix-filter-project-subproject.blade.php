<form action="{{ route('updateUserSettings') }}" method="post">
    @csrf
    @method('PUT')
    <input type="hidden" name="action" value="updateViewAllMatrix"/>
    <input type="hidden" name="_entity" value="{{$type}}"/>
    <div class="bg-white rounded w-full my-2 p-2">
        <div class="w-full my-1 grid grid-cols-12 gap-2">
            {{-- <div class="col-span-3">
                Project:
                <x-calendar.sidebar-filter-project 
                    tableName="projects" 
                    name="project_id" 
                    id="project_id" 
                    typeToLoadListener="qaqc_wir" 
                    selected="{{$viewportParams['project_id']}}"
                    />
            </div> --}}
            <div class="col-span-3">
                Sub-Project:
                <x-calendar.sidebar-filter-sub-project 
                    tableName="sub_projects" 
                    name="sub_project_id" 
                    id="sub_project_id" 
                    typeToLoadListener="qaqc_wir" 
                    selected="{{$viewportParams['sub_project_id']}}"
                    />
            </div>
            <div class="col-span-3">
                Production Routing
                <x-calendar.sidebar-filter-prod-routing 
                    tableName="prod_routings" 
                    name="prod_routing_id" 
                    id="prod_routing_id" 
                    typeToLoadListener="qaqc_wir" 
                    selected="{{$viewportParams['prod_routing_id']}}"
                    />
            </div>
        </div>
        <x-renderer.button type='primary' htmlType="submit" icon="fa-sharp fa-solid fa-check">Apply Filter</x-renderer.button>
    </div>
</form>