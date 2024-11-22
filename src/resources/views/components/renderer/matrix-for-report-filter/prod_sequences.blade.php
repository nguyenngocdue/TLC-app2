<form action="{{ route('updateUserSettings') }}" method="post">
    @csrf
    @method('PUT')
    <input type="hidden" name="action" value="updateViewAllMatrix"/>
    <input type="hidden" name="_entity" value="{{$type}}"/>
    <div class="bg-white rounded w-full my-2 p-2">
        <div class="w-full my-1 grid grid-cols-12 gap-2">
            <div class="col-span-12 sm:col-span-6 md:col-span-4 lg:col-span-3 xl:col-span-2">
                Project <span class='text-red-400' title='required'>*</span>
                <x-renderer.view-all-matrix-filter.ProjectFilter 
                    tableName="projects" 
                    name="project_id" 
                    id="project_id" 
                    typeToLoadListener="qaqc_wir" 
                    selected="{{$viewportParams['project_id']}}"
                    typePlural="prod_sequences"
                    :dataSource="$filterDataSource['projects']"
                    />
            </div>
            <div class="col-span-12 sm:col-span-6 md:col-span-4 lg:col-span-3 xl:col-span-2">
                Sub-Project <span class='text-red-400' title='required'>*</span>
                <x-renderer.view-all-matrix-filter.SubProjectFilter 
                    tableName="sub_projects" 
                    name="sub_project_id" 
                    id="sub_project_id" 
                    typeToLoadListener="qaqc_wir" 
                    selected="{{$viewportParams['sub_project_id']}}"
                    typePlural="prod_sequences"
                    :dataSource="$filterDataSource['sub_projects']"
                />
            </div>
            <div class="col-span-12 sm:col-span-6 md:col-span-4 lg:col-span-3 xl:col-span-2">
                Production Routing <span class='text-red-400' title='required'>*</span>
                <x-renderer.view-all-matrix-filter.ProdRoutingFilter 
                    tableName="prod_routings" 
                    name="prod_routing_id" 
                    id="prod_routing_id" 
                    typeToLoadListener="qaqc_wir" 
                    selected="{{$viewportParams['prod_routing_id']}}"
                    typePlural="prod_sequences"
                    :dataSource="$filterDataSource['prod_routings']"
                    />
            </div>
            {{-- <div class="col-span-12 sm:col-span-6 md:col-span-4 lg:col-span-3 xl:col-span-2">
                Production Discipline
                <x-renderer.view-all-matrix-filter.ProdDisciplineFilter 
                    tableName="prod_disciplines" 
                    name="prod_discipline_id" 
                    id="prod_discipline_id" 
                    typeToLoadListener="qaqc_wir" 
                    allowClear="true"
                    selected="{{$viewportParams['prod_discipline_id']}}"
                    typePlural="prod_sequences"
                    :dataSource="$filterDataSource['']"
                    />
            </div> --}}
            {{-- <div class="col-span-12 sm:col-span-6 md:col-span-4 lg:col-span-3 xl:col-span-2">
                Production Routing Link
                <x-renderer.view-all-matrix-filter.ProdRoutingLinkFilter 
                    tableName="prod_routing_links" 
                    name="prod_routing_link_id[]" 
                    id="getRoutingLinks" 
                    typeToLoadListener="listener_config" 
                    allowClear="true"
                    :selected="$viewportParams['prod_routing_link_id']"
                    multiple="true"
                    />
            </div> --}}
        </div>
        <x-renderer.button type='primary' htmlType="submit" icon="fa-sharp fa-solid fa-check">Apply Filter</x-renderer.button>
    </div>
</form>