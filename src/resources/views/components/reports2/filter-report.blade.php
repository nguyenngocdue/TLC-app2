@php
    $route = 'https://127.0.0.1:38002/dashboard/rp_reports/34';
@endphp


<div class="no-print justify-end pb-5"></div> 
<div class="grid grid-cols-12 gap-4 items-baseline px-4">
    <!-- Mode Dropdown -->
    <div class="col-span-2 w-full no-print rounded-lg border bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 mb-5 p-3">
        <x-reports.dropdown8 
            title="Mode" 
            name="current_mode" 
            routeName="report-prod_sequence_020"
            :allowClear="false" 
            :dataSource="$keyNameModes" 
            typeReport="reports" 
            entity="prod_sequences"
            modeOption="020"
            :itemsSelected="$params" 
        />
    </div>
    
    <!-- Advanced Filter Section -->
    <div class="col-span-10">
        <div class="w-full no-print rounded-lg border bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 mb-5 p-3">
            <label for="" class="flex flex-1 text-gray-700 text-lg font-bold dark:text-white">Advanced Filter</label>
            
            <form action="{{ $route }}" id="{{ $reportName }}" method="GET">
                @csrf
                @method('PUT')
                {{-- <input type="hidden" name='_entity' value="{{ $entity }}">
                <input type="hidden" name='action' value="updateReport{{ Str::ucfirst($typeReport) }}">
                <input type="hidden" name='type_report' value="{{ $typeReport }}"> --}}
                
                <div class="grid grid-cols-12 gap-4 items-baseline">
                    @foreach ($filterDetails as $filter)
                        
                        @php
                            $title = is_null($x = $filter->getColumn->title) ? '(Set title for column)' : $x;
                            $keyParam = str_replace('name', 'id', $x = $filter->getColumn->data_index);
                            $selected =  $params[$keyParam];
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
        </div>
    </div>
</div>

