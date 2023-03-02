<form id="{{$type}}" action="{{route($type . '.index')}}" method="GET">
    <input type="hidden" name="_entity" value="{{$type}}">
    
    <div class="rounded-lg border bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 mb-5 p-3">
        <div class="flex ">
            <label for="" class="flex flex-1 text-gray-700 text-lg font-bold dark:text-white">Advanced Filter</label>
                <button type="button" class=" text-gray-900 bg-orange-400 focus:shadow-outline border border-gray-200 focus:outline-none hover:bg-purple-400  font-medium rounded-lg text-sm px-4 py-2 text-center inline-flex items-center dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700" @click="clearAdvanceFilter()">
                <i class="fa-sharp fa-solid fa-circle-xmark"></i>
                <span class="ml-2">Reset Filter</span>
                </button>
        </div>
                
        <div class="grid lg:grid-cols-6 lg:gap-2 md:grid-cols-4 md:gap-4 sm:grid-cols-2 sm:gap-10">
            @php
            $timeControls = ['picker_time','picker_date','picker_month','picker_week','picker_quarter','picker_year','picker_datetime'];
            @endphp
            @foreach($props as $key => $value)
            <div>
                @php
                $label = $value['label'];
                $columnName = $value['column_name'];
                $columnType = $value['column_type'];
                $control = $value['control'];
                $relationships = $value['relationships'];
                $valueControl = $valueAdvanceFilters[substr($key,1)] ?? null;
                @endphp
                @if (is_null($control))
                <h2 class="text-red-400">{{"Control of this $columnName has not been set"}}</h2>
                @endif
                {{-- Invisible anchor for scrolling when users click on validation fail message --}}
                <div class="truncate">
                    <label for={{$columnName}} class="text-gray-900 dark:text-gray-300 text-base font-normal" >{{$label}}</label>
                </div>
                @switch ($control)
                @case($timeControls[0])
                <x-controls.time-picker2 :name="$columnName" :value="$valueControl"/>
                @if(Session::has($columnName))
                    <p class="ml-3 mt-1 text-xs font-light text-red-600">{{ Session::get($columnName) }}</p>
                @endif
                @break
                @case($timeControls[1])
                @case($timeControls[2])
                @case($timeControls[3])
                @case($timeControls[4])
                @case($timeControls[5])
                @case($timeControls[6])
                <x-controls.date-picker2 :name="$columnName" :value="$valueControl"/>
                @if(Session::has($columnName))
                    <p class="ml-3 mt-1 text-xs font-light text-red-600">{{ Session::get($columnName) }}</p>
                @endif
                @break
                @case('id')
                @case('text')
                @case('number')
                @case('textarea')
                @case('parent_id')
                @case('doc_id')
                <x-controls.text name={{$columnName}} value={{$valueControl}} />
                @break
                @case('toggle')
                <x-controls.toggle name={{$columnName}} value={{$valueControl}} />
                @break
                @case ('dropdown')
                @case ('radio')
                @case ('dropdown_multi')
                @case('checkbox')
                    <x-controls.dropdown3 :name="$columnName" :relationships="$relationships" :valueSelected="$valueControl"/>
                @break
                @case('status')
                    @php
                        $libStatus = App\Http\Controllers\Workflow\LibStatuses::getFor($type);
                    @endphp
                    <select id="{{$columnName}}" class="select2-hidden-accessible" multiple="multiple" style="width: 100%;" name="{{$columnName}}[]" tabindex="-1" aria-hidden="true">
                        @foreach($libStatus as $value)
                        <option value="{{$value['name']}}" @selected($valueControl ? in_array($value['name'],$valueControl) : null) >{{$value['title'] ?? $value['name']}}</option>
                        @endforeach
                    </select>
                    <script>
                            $('[id="'+"{{$columnName}}"+'"]').select2({
                                placeholder: "Please select..."
                                , allowClear: true
                                , templateResult: select2FormatState
                            });
                    </script>
                @break
                @case('parent_type')
                <x-controls.parent-type :type="$type" :name="$columnName" :valueSelected="$valueControl"/>
                @break
                @default
                <x-feedback.alert type="warning" title="Control" message="Unknown how to render [{{$control}}]" />
                @break
                @endswitch
            </div>
            @endforeach
            
    
        </div>
        <div >
            <button type="submit" name="action" value="updateAdvanceFilter" class="mt-4 focus:shadow-outline rounded bg-emerald-500 py-1.5 px-4 font-semibold text-base text-white hover:bg-purple-400 focus:outline-none">
                Apply
            </button>
        </div>
    </div>
    
</form>
<script>
    function clearAdvanceFilter(){
        $('[id="'+"{{$type}}"+'"]').append('<input type="hidden" name="action" value="clearAdvanceFilter">')
        $('[id="'+"{{$type}}"+'"]').submit()
    }
</script>
