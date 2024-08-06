<div class="no-print justify-end pb-5"></div> 
<div class="grid grid-cols-12 gap-4 items-baseline px-4">
    <!-- Mode Dropdown -->
    @if(count($dataDropdownRpLink) > 1)
    <div class="col-span-2 w-full no-print rounded-lg border bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 mb-5 p-3">
        <x-reports2.dropdown9 
            title="Mode" 
            name="current_report_link" 
            entityType="{{$entity_type}}"
            entityType2="{{$entityType2}}"
            reportId="{{$reportId}}"
            routeName="{{$routeFilter}}"
            :dataSource="$dataDropdownRpLink" 
            :currentParams="$currentParams" 
            :filterDetails="$filterDetails"
        />
    </div>
    @endif
    <!-- Advanced Filter Section -->
    <div class="col-span-{{count($dataDropdownRpLink) > 1 ? 10 : 12 }}">
        <div class="w-full no-print rounded-lg border bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 mb-5 p-3">
            <label for="" class="flex flex-1 text-gray-700 text-lg font-bold dark:text-white">Advanced Filter</label>
            @if($filterDetails->toArray())
                <form action="{{$routeFilter}}" id="{{ $reportName }}" method="POST">
                    @csrf
                    <input type="hidden" name='action' value="updateReport2">
                    <input type="hidden" name='current_report_link' value="{{$currentParams['current_report_link']}}">
                    <input type="hidden" name='entity_type' value="{{$entityType}}">
                    <input type="hidden" name='entity_type2' value="{{$entityType2}}">
                    <input type="hidden" name='report_id' value="{{$reportId}}">
                
                    <div class="grid grid-cols-12 gap-4 items-baseline">
                        @foreach ($filterDetails as $filter)
                            
                            @php
                                $title = is_null($x = $filter->getColumn->title) ? '(Set title for column)' : $x;
                                $keyParam = str_replace('name', 'id', $x = $filter->getColumn->data_index);
                                $selected =  $currentParams[$keyParam] ?? null;
                            @endphp
                            
                            <div class="col-span-2">
                                <a target="_blank" href="{{ route('rp_report_filter_details.edit', $filter->id) }}">
                                    <span class='px-1'>{{$title}}</span>
                                </a>
                                <x-renderer.report2.filter-report-item 
                                    :filterDetail="$filter"
                                    :selected="$selected"    
                                />
                            </div>
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
                        Config Advanced Filter
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
