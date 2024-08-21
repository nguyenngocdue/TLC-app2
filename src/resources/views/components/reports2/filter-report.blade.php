{{-- Validation Filters --}}
@if($paramsError)
    @foreach($paramsError as $filter)
        <x-feedback.alert type='error' message='You must specify {{$filter}}.'></x-feedback.alert>
    @endforeach
@endif

<div class="no-print justify-end pb-5"></div> 
<div class="grid grid-cols-12 gap-4 items-baseline px-4 skeleton">
    <!-- Mode Dropdown -->
    @if(count($rpFilterLinks->toArray()) > 0)
        <div class="col-span-2 w-full no-print rounded-lg border bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 mb-5 p-3">
                <div class="text-left whitespace-nowrap">
                    <span class="flex flex-1 text-gray-700 text-lg font-bold dark:text-white">Report Link</span>
                </div>
                <x-reports2.dropdown9 
                    name="report_link" 
                    :rpFilterLinks="$rpFilterLinks" 
                />
        </div>
    @endif
        <!-- Advanced Filter Section -->
        <div class="col-span-{{count($rpFilterLinks->toArray()) > 0 ? 10 : 12 }}">
            <div class="w-full no-print rounded-lg border bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 mb-5 p-3">
                <label for="" class="flex flex-1 text-gray-700 text-lg font-bold dark:text-white">Advanced Filter</label>
                @if($rpFilters->toArray())
                    <form action="{{$routeFilter}}" id="{{ $reportName }}" method="POST">
                        @csrf
                        <input type="hidden" name='action' value="updateReport2">
                    <input type="hidden" name='entity_type' value="{{$entityType}}">
                        <input type="hidden" name='entity_type2' value="{{$entityType2}}">
                        <input type="hidden" name='report_id' value="{{$rpId}}">
                    
                        <div class="grid grid-cols-12 gap-4 items-baseline">
                            @foreach ($rpFilters as $filter)

                                @if($filter->is_active)
                                    @php
                                        $text = 'App\Utils\Support\StringReport'::makeTitleFilter($filter->entity_type);
                                        $title = ($x = $filter->title) ? $x : ($filter->is_multiple ? Str::plural($text) : Str::singular($text)); 
                                        $selected = $currentParams[$filter->data_index] ?? [];
                                    @endphp
                                    <div class="col-span-2">
                                        <div class="relative inline-block group">
                                            <span class="px-1 cursor-pointer">{{$title}}
                                                @if($filter->is_required)
                                                    <span class="text-red-400" title="required">*</span>
                                                @endif
                                            </span>
                                            <!-- Hidden nodes, shown on hover -->
                                            @if (App\Utils\Support\CurrentUser::isAdmin())
                                                <div class="absolute right-full top-0 hidden group-hover:block space-y-2 bg-gray-500 p-1 rounded shadow-lg z-10">
                                                    <a target="_blank" href="{{ route('rp_filters.edit', $filter->id) }}" class="flex items-center bg-blue-100 hover:bg-blue-200 px-2 pt-1 w-full text-left rounded text-blue-700">
                                                        <i class="fas fa-link mr-2"></i>
                                                        <span class="truncate">Rp Filter</span>
                                                    </a>
                                                    @if( $filter->listen_reducer_id)
                                                        <a target="_blank" href="{{ route('rp_listen_reducers.edit', $filter->listen_reducer_id) }}" class="flex items-center bg-green-100 hover:bg-green-200 px-2 pt-1 pb-1 w-full text-left rounded text-green-700">
                                                            <i class="fas fa-link mr-2"></i>
                                                            <span class="truncate">Rp Listen Reducer</span>
                                                        </a>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                        <x-renderer.report2.filter-report-item
                                            :filter="$filter"
                                            :selected="$selected"    
                                        />
                                        @if($filter->default_value && !$filter->is_multiple)
                                            @php
                                                $defaultValue = explode(',',$filter->default_value);
                                            @endphp
                                            @if(count($defaultValue) > 1)
                                                <x-renderer.heading level=7 class='pt-1 text-left text-red-700 border-red-300'>(Configuration Error)</x-renderer.heading>
                                            @endif
                                        @endif
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
                    <x-renderer.button class="item-center" href="{{ route('rp_reports.edit', $rpId) }}" type="warning" title="{{ $reportName }}">
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