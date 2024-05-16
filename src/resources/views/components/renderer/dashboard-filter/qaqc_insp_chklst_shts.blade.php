<form action="{{ route('updateUserSettings') }}" method="post">
    @csrf
    @method('PUT')
    <input type="hidden" name="action" value="updateDashboardMatrix"/>
    <input type="hidden" name="_entity" value="{{$type}}"/>
    <div class="bg-white rounded w-full my-2 p-2">
        <div class="w-full my-1 grid grid-cols-10 gap-2">
            <div class="col-span-2">
                Sub-Project
                <x-renderer.dashboard-filter.SubProjectFilter 
                    tableName="sub_projects" 
                    name="sub_project_id" 
                    id="sub_project_id" 
                    typeToLoadListener="listener_config" 
                    selected="{{$viewportParams['sub_project_id']}}"
                    :dataSource="$dataSource['sub_projects']"
                />
            </div>

            <div class="col-span-2">
                Production Routing
                <x-renderer.dashboard-filter.ProdRoutingFilter
                    tableName="prod_routings" 
                    name="prod_routing_id" 
                    id="prod_routing_id" 
                    {{-- typePlural="qaqc_insp_chklst_shts" --}}
                    typeToLoadListener="listener_config" 
                    selected="{{$viewportParams['prod_routing_id']}}"
                    :dataSource="$dataSource['prod_routings']"
                    />
            </div>

            <div class="col-span-2">
                Checklist Type
                <x-renderer.dashboard-filter.QaqcInspTmplFilter
                    tableName="qaqc_insp_tmpls" 
                    name="qaqc_insp_tmpl_id" 
                    id="qaqc_insp_tmpl_id" 
                    typeToLoadListener="listener_config" 
                    selected="{{$viewportParams['qaqc_insp_tmpl_id']}}"
                    :dataSource="$dataSource['qaqc_insp_tmpls']"
                    />
            </div>

        </div>
        <x-renderer.button type='primary' htmlType="submit" icon="fa-sharp fa-solid fa-check">Apply Filter</x-renderer.button>
    </div>
</form>