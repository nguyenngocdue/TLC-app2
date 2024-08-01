<div class="no-print justify-end pb-5"></div> 
<div class="grid grid-cols-12 gap-4 items-baseline px-4">
    <!-- Mode Dropdown -->
    <div class="col-span-2 w-full no-print rounded-lg border bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 mb-5 p-3">
        <x-reports.dropdown8 
            title="Mode" 
            name="forward_to_mode" 
            routeName="report-prod_sequence_020"
            :allowClear="false" 
            :dataSource="$keysNames" 
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
            
            <form action="{{ route('updateUserSettings') }}" method="post">
                @csrf
                @method('PUT')
                <input type="hidden" name="action" value="updateViewAllMatrix" />
                <input type="hidden" name="_entity" value="{{ $type }}" />
                
                <div class="grid grid-cols-12 gap-4 items-baseline">
                    @foreach ($filterDetails as $filter)
                        @php
                            $title = is_null($x = $filter->getColumn->title) ? '(Set title for column)' : $x;
                        @endphp
                        <div class="col-span-2">
                            <a target="_blank" href="{{ route('rp_report_filter_details.edit', $filter->id) }}">
                                <span class='px-1'>{{$title}}</span>
                            </a>
                            <x-renderer.report2.filter-report-item :filterDetail="$filter" />
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

<script type="text/javascript">
    function resetFilter() {
        $('[id="' + "{{ $entity }}" + '"]').append(
            '<input type="hidden" name="form_type" value="resetParamsReport">'
        );
    }
</script>
