
    <form id="{{$type}}" action="{{route($type . '.index')}}" method="GET">
        <input type="hidden" name="_entity" value="{{$type}}">
        <div id="modal_basic_filter" class="rounded-lg border bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 mb-5 px-3 py-1 
        {{$currentFilter == 'basic_filter' ? '' : 'hidden'}}">
            <div class="flex h-10">
                <div class="flex flex-1">
                    <label for="" class=" flex items-center justify-center text-gray-700 text-lg font-bold dark:text-white h-full">Basic Filter</label>
                </div>
                <div class="flex h-full">
                    <x-advanced-filter.choose-basic-filter3 name="choose_basic_filter" type="{{$type}}" />
                    <x-renderer.button htmlType="submit" type="secondary" name="action" value="updateBasicFilter" class="ml-2"><i class="fa-regular fa-filter"></i></x-renderer.button>
                    <x-renderer.button type="danger" click="deletedBasicFilter()" class="mx-2"><i class="fa-solid fa-trash"></i></x-renderer.button>
                    <button type="button" class="pl-2 text-2xl border-l" @click="toogleAdvanceFilter()">
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>
                </div>
            </div>
        </div>
        @php
            $render = false;
            if(!$currentFilter || $currentFilter == 'advance_filter'){
                $render = true;
            }
        @endphp
        <div id="modal_advance_filter" class="rounded-lg border bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 mb-5 p-3 {{$render ? '' : 'hidden'}}">
            <div class="flex h-10">
                <label for="" class="flex flex-1 text-gray-700 text-lg font-bold dark:text-white">Advanced Filter</label>
                <div class="flex">
                    <button type="button" class="pl-2 text-2xl border-l" @click="toogleAdvanceFilter()">
                        <i class="fa-solid fa-chevron-up"></i>
                    </button>
                </div>
            </div>
            <div class="grid lg:grid-cols-6 lg:gap-2 md:grid-cols-4 md:gap-4 sm:grid-cols-2 sm:gap-10 h-fit">
                <div class="h-auto">
                    <div class="flex">
                        <x-advanced-filter.text3  name="basic_filter" value="" placeholder='Basic filter name...' onKeyPress="onKeyPress(event)"/>
                        <x-renderer.button type="secondary" click="saveBasicFilter()" class="ml-2"><i class="fa-solid fa-floppy-disk"></i></x-renderer.button>
                    </div>
                    <div class="w-full max-h-52 overflow-y-auto mt-2 text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @foreach($basicFilter as $value)
                        <button value="{{$value}}" @click='updateBasicFilter2()' name="choose_basic_filter" class="relative {{$valueBasicFilter == $value ? 'text-blue-700' : ''}} inline-flex items-center w-full px-4 py-2 text-sm font-medium rounded-b-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white">
                            @if($valueBasicFilter == $value)
                            <i class="fa-solid fa-check mr-2"></i>
                            @else
                            <i class="fa-solid fa-plus mr-2"></i>
                            @endif
                            {{$value}}
                        </button>
                        @endforeach
                    </div>
                </div>
                <div class="col-span-5">
                    <div class="grid lg:grid-cols-5 lg:gap-2 md:grid-cols-3 md:gap-4 sm:grid-cols-1 sm:gap-10">
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
                        <div class="truncate" title={{$columnName}}>
                            <label for={{$columnName}} class="text-gray-900 dark:text-gray-300 text-base font-normal" >{{$label}}</label>
                        </div>
                        @switch ($control)
                        @case('picker_time')
                        <x-advanced-filter.picker-time3  :name="$columnName" :value="$valueControl"/>
                        @if(Session::has($columnName))
                            <p class="ml-3 mt-1 text-xs font-light text-red-600">{{ Session::get($columnName) }}</p>
                        @endif
                        @break
                        @case('picker_month')
                        <x-advanced-filter.picker-month3  :name="$columnName" :value="$valueControl"/>
                        @if(Session::has($columnName))
                            <p class="ml-3 mt-1 text-xs font-light text-red-600">{{ Session::get($columnName) }}</p>
                        @endif
                        @break
                        @case('picker_week')
                        <x-advanced-filter.picker-week3  :name="$columnName" :value="$valueControl"/>
                        @if(Session::has($columnName))
                            <p class="ml-3 mt-1 text-xs font-light text-red-600">{{ Session::get($columnName) }}</p>
                        @endif
                        @break
                        @case('picker_year')
                        <x-advanced-filter.picker-year3  :name="$columnName" :value="$valueControl"/>
                        @if(Session::has($columnName))
                            <p class="ml-3 mt-1 text-xs font-light text-red-600">{{ Session::get($columnName) }}</p>
                        @endif
                        @break
                        @case('picker_quarter')
                        <x-advanced-filter.picker-quarter3  :name="$columnName" :value="$valueControl"/>
                        @if(Session::has($columnName))
                            <p class="ml-3 mt-1 text-xs font-light text-red-600">{{ Session::get($columnName) }}</p>
                        @endif
                        @break
                        @case('picker_date')
                        @case('picker_datetime')
                        <x-advanced-filter.picker-date3  :name="$columnName" :value="$valueControl"/>
                        @if(Session::has($columnName))
                            <p class="ml-3 mt-1 text-xs font-light text-red-600">{{ Session::get($columnName) }}</p>
                        @endif
                        @break
                        @case('id')
                        @case('doc_id')
                        @case('parent_id')
                        <div class="mt-1">
                            <x-advanced-filter.text3  name={{$columnName}} value={{$valueControl}} placeholder="Comma separated numbers are allowed"/>
                        </div>
                        @break
                        @case('text')
                        @case('number')
                        @case('textarea')
                        <div class="mt-1">
                            <x-advanced-filter.text3  name={{$columnName}} value={{$valueControl}}/>
                        </div>
                        @break
                        @case('toggle')
                        <x-advanced-filter.toggle3  name={{$columnName}} value={{$valueControl}} />
                        @break
                        @case ('dropdown')
                        @case ('radio')
                        @case ('dropdown_multi')
                        @case('checkbox')
                            <x-advanced-filter.dropdown3  :name="$columnName" :relationships="$relationships" :valueSelected="$valueControl"/>
                        @break
                        @case('status')
                            @php
                                $libStatus = App\Http\Controllers\Workflow\LibStatuses::getFor($type);
                            @endphp
                            <div class="mt-1" title={{$control}}>
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
                        @case('parent_type')
                        <x-advanced-filter.parent-type3 :type="$type" :name="$columnName" :valueSelected="$valueControl"/>
                        @break
                        @default
                        <x-feedback.alert type="warning" title="Control" message="[{{$control}}] ???" />
                        @break
                        @endswitch
                        </div>
                        @endforeach
                    </div>
                    <div class="flex py-2 justify-end">
                        <x-renderer.button htmlType="submit" type="primary" name="action" value="updateAdvanceFilter"><i class="fa-sharp fa-solid fa-check"></i> Apply Filter</x-renderer.button>
                        <x-renderer.button type="secondary" click="clearAdvanceFilter()" class="ml-2"><i class="fa-sharp fa-solid fa-circle-xmark"></i> Reset</x-renderer.button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script>
        function clearAdvanceFilter(){
            $('[id="'+"{{$type}}"+'"]').append('<input type="hidden" name="action" value="clearAdvanceFilter">')
            $('[id="'+"{{$type}}"+'"]').submit()
        }
        function onKeyPress(event){
            if(event.keyCode === 13){
                saveBasicFilter();
            }
        }
        function saveBasicFilter(){
            $('[id="'+"{{$type}}"+'"]').append('<input type="hidden" name="action" value="saveBasicFilter">')
            $('[id="'+"{{$type}}"+'"]').submit()
        }
        function updateBasicFilter(){
            $('[id="'+"{{$type}}"+'"]').append('<input type="hidden" name="action" value="updateBasicFilter">')
            $('[id="'+"{{$type}}"+'"]').submit()
        }
        function updateBasicFilter2(){
            $('[id="'+"{{$type}}"+'"]').append('<input type="hidden" name="action" value="updateBasicFilter2">')
            $('[id="'+"{{$type}}"+'"]').submit()
        }
        function deletedBasicFilter(){
            $('[id="'+"{{$type}}"+'"]').append('<input type="hidden" name="action" value="deletedBasicFilter">')
            $('[id="'+"{{$type}}"+'"]').submit()
        }
        function toogleAdvanceFilter(){
            $('[id="modal_basic_filter"]').toggleClass('hidden')
            $('[id="modal_advance_filter"]').toggleClass('hidden')
        }
    </script>


