{{-- @dump($currentParams) --}}
{{-- @if($warningFilters)
    @foreach($warningFilters as $filter)
        <x-feedback.alert type='error' message='You must specify {{$filter}}.'></x-feedback.alert>
    @endforeach
@endif --}}

<div class="no-print justify-end pb-5"></div> 
<div class="grid grid-cols-12 gap-4 items-baseline px-4 skeleton">
    <!-- Mode Dropdown -->
    {{-- @if(!empty($filterModes->toArray())) --}}
        <div class="col-span-2 w-full no-print rounded-lg border bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 mb-5 p-3">
                <div class="text-left whitespace-nowrap">
                    <span class="flex flex-1 text-gray-700 text-lg font-bold dark:text-white">Report Link</span>
                </div>
            @if(count($filterLinkDetails) > 1)
                <x-reports2.dropdown9 
                    title="Mode" 
                    name="report_link" 
                    entityType="{{$entity_type}}"
                    entityType2="{{$entityType2}}"
                    reportId="{{$reportId}}"
                    routeName="{{$routeFilter}}"
                    :filterLinkDetails="$filterLinkDetails" 
                    :currentParams="$currentParams" 
                />
            @else
                <x-renderer.button href="{{ route('rp_reports.edit', $reportId) }}" type="warning" title="{{ $reportName }}">
                        Config Filter Modes
                </x-renderer.button>
            @endif
        </div>
    {{-- @endif --}}
        <!-- Advanced Filter Section -->
        <div class="col-span-{{count($rpFilters->toArray()) > 0 ? 10 : 12 }}">
            <div class="w-full no-print rounded-lg border bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 mb-5 p-3">
                <label for="" class="flex flex-1 text-gray-700 text-lg font-bold dark:text-white">Advanced Filter</label>
                @if($rpFilters->toArray())
                    <form action="{{$routeFilter}}" id="{{ $reportName }}" method="POST">
                        @csrf
                        <input type="hidden" name='action' value="updateReport2">
                        <input type="hidden" name='entity_type' value="{{$entityType}}">
                        <input type="hidden" name='entity_type2' value="{{$entityType2}}">
                        <input type="hidden" name='report_id' value="{{$reportId}}">
                    
                        <div class="grid grid-cols-12 gap-4 items-baseline">
                            @foreach ($rpFilters as $filters)
                                @if($filters->is_active)
                                    @php
                                        $t = $filters->is_multiple ? Str::plural($filters->entity_type) : Str::singular($filters->entity_type); 
                                        $editedDataIndex = $filters->is_multiple ? Str::plural($filters->data_index) : Str::singular($filters->data_index); 
                                        $title = is_null($t) ? '(Set title for column)' : ucwords(str_replace('_', ' ',$t)) ;
                                        $selected =  $currentParams[$editedDataIndex];
                                    @endphp
                                    <div class="col-span-2">
                                        <a target="_blank" href="{{ route('rp_filters.edit', $filters->id) }}" title="id : {{$filters->id}}">
                                            <span class='px-1'>{{$title}}</span>
                                            @if($filters->is_required)
                                                <span class="text-red-400" title="required">*</span>
                                            @endif
                                        </a>
                                        <x-renderer.report2.filter-report-item 
                                            :filters="$filters"
                                            :selected="$selected"    
                                        />
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        
                        <div class="py-2">
                            <x-renderer.button htmlType="submit" type="primary">
                                <i class="fa-sharp fa-solid fa-check"></i> Apply Filter
                            </x-renderer.button>
                            <x-renderer.button htmlType="submit" click="resetFilter()" type="secondary">
                                <i class="fa-sharp fa-solid fa-circle-xmark pr-1"></i> Reset
                            </x-renderer.button>
                        </div>
                    </form>
                @else
                    <x-renderer.button class="item-center" href="{{ route('rp_reports.edit', $reportId) }}" type="warning" title="{{ $reportName }}">
                            Config Filter Details
                    </x-renderer.button>
                @endif
            </div>
        </div> 
</div>

<script type="text/javascript">
    function resetFilter() {
        $('[id="' + "{{ $reportName }}" + '"]').append(
            '<input type="hidden" name="form_type" value="resetParamsReport2">')
    }
</script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
        const refreshPage = {!! json_encode($refreshPage) !!};
        const reportId = {!! json_encode($reportId) !!};
        if (refreshPage) {
            window.location.href = '{{ route("rp_reports.show", ":id") }}'.replace(':id', reportId);
        }
    });
</script>