<form id="{{$type}}" action="{{route($type . '.index')}}" method="GET">
    <input type="hidden" name="_entity" value="{{$type}}">
    
    <div class="rounded-lg border bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 mb-5 p-3">
        <div class="flex ">
            <label for="" class="flex flex-1 text-gray-700 text-lg font-bold dark:text-white">Advanced Filter</label>
                <button type="button" class="px-2.5 py-2  inline-block  font-medium text-sm leading-tight rounded focus:ring-0 transition duration-150 ease-in-out bg-purple-600 text-white shadow-md hover:bg-purple-700 hover:shadow-lg focus:bg-purple-700 focus:shadow-lg focus:outline-none active:bg-purple-800 active:shadow-lg" @click="clearAdvanceFilter()">
                <i class="fa-sharp fa-solid fa-circle-xmark"></i>
                <span class="ml-1">RESET FILTER</span>
                </button>
        </div>
                
        <div class="grid lg:grid-cols-6 lg:gap-2 md:grid-cols-4 md:gap-4 sm:grid-cols-2 sm:gap-10">
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
                @case('picker_time')
                <x-advanced-filter.picker-time3 :name="$columnName" :value="$valueControl"/>
                @if(Session::has($columnName))
                    <p class="ml-3 mt-1 text-xs font-light text-red-600">{{ Session::get($columnName) }}</p>
                @endif
                @break
                @case('picker_month')
                <x-advanced-filter.picker-month3 :name="$columnName" :value="$valueControl"/>
                @if(Session::has($columnName))
                    <p class="ml-3 mt-1 text-xs font-light text-red-600">{{ Session::get($columnName) }}</p>
                @endif
                @break
                @case('picker_week')
                <x-advanced-filter.picker-week3 :name="$columnName" :value="$valueControl"/>
                @if(Session::has($columnName))
                    <p class="ml-3 mt-1 text-xs font-light text-red-600">{{ Session::get($columnName) }}</p>
                @endif
                @break
                @case('picker_year')
                <x-advanced-filter.picker-year3 :name="$columnName" :value="$valueControl"/>
                @if(Session::has($columnName))
                    <p class="ml-3 mt-1 text-xs font-light text-red-600">{{ Session::get($columnName) }}</p>
                @endif
                @break
                @case('picker_quarter')
                <x-advanced-filter.picker-quarter3 :name="$columnName" :value="$valueControl"/>
                @if(Session::has($columnName))
                    <p class="ml-3 mt-1 text-xs font-light text-red-600">{{ Session::get($columnName) }}</p>
                @endif
                @break
                @case('picker_date')
                @case('picker_datetime')
                <x-advanced-filter.picker-date3 :name="$columnName" :value="$valueControl"/>
                @if(Session::has($columnName))
                    <p class="ml-3 mt-1 text-xs font-light text-red-600">{{ Session::get($columnName) }}</p>
                @endif
                @break
                @case('id')
                @case('doc_id')
                @case('parent_id')
                <div class="mt-1">
                    <x-advanced-filter.text3 name={{$columnName}} value={{$valueControl}} placeholder="Comma separated numbers are allowed"/>
                </div>
                @break
                @case('text')
                @case('number')
                @case('textarea')
                <div class="mt-1">
                    <x-advanced-filter.text3 name={{$columnName}} value={{$valueControl}}/>
                </div>
                @break
                @case('toggle')
                <x-advanced-filter.toggle3 name={{$columnName}} value={{$valueControl}} />
                @break
                @case ('dropdown')
                @case ('radio')
                @case ('dropdown_multi')
                @case('checkbox')
                    <x-advanced-filter.dropdown3 :name="$columnName" :relationships="$relationships" :valueSelected="$valueControl"/>
                @break
                @case('status')
                    @php
                        $libStatus = App\Http\Controllers\Workflow\LibStatuses::getFor($type);
                    @endphp
                    <div class="mt-1">
                        <select id="{{$columnName}}" class="select2-hidden-accessible" multiple="multiple" style="width: 100%;" name="{{$columnName}}[]" tabindex="-1" aria-hidden="true">
                            @foreach($libStatus as $value)
                            <option value="{{$value['name']}}" @selected($valueControl ? in_array($value['name'],$valueControl) : null) >{{$value['title'] ?? $value['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <script>
                            $('[id="'+"{{$columnName}}"+'"]').select2({
                                placeholder: "Please select..."
                                , allowClear: true
                                , templateResult: select2FormatState
                            });
                    </script>
                @break
                {{-- @case('parent_type')
                <x-advanced-filter.parent-type3 :type="$type" :name="$columnName" :valueSelected="$valueControl"/>
                @break --}}
                @default
                <x-feedback.alert type="warning" title="Control" message="Unknown how to render [{{$control}}]" />
                @break
                @endswitch
            </div>
            @endforeach
            
    
        </div>
        <div >
            <button type="submit" name="action" value="updateAdvanceFilter" class="mt-4 px-3 py-2  inline-block  font-medium text-sm leading-tight uppercase rounded focus:ring-0 transition duration-150 ease-in-out bg-purple-600 text-white shadow-md hover:bg-purple-700 hover:shadow-lg focus:bg-purple-700 focus:shadow-lg focus:outline-none active:bg-purple-800 active:shadow-lg">
                APPLY
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
