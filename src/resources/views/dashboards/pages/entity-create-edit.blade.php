@extends('layouts.app')
@php
$editType = Str::plural($type);
$id = $action === "edit" ? $values->id : "";
$status = $status ?? $values->status ?? null;
[$status,$statuses,$props,$actionButtons,$transitions,$buttonSave,$propsIntermediate] = App\Utils\Support\WorkflowFields::resolveSuperProps($superProps ,$status,$type,$isCheckColumnStatus);
$result = App\Utils\Support\WorkflowFields::parseFields($props, $values, $defaultValues,$status,$type);
@endphp
@section('topTitle', $topTitle)
@section('title', $title )
@section('status', $status)

@section('content')



{{-- @dump($values) --}}
<script>
    k = @json($listenerDataSource);

    listenersOfDropdown2 = @json($listeners2);
    filtersOfDropdown2 = @json($filters2);
    
    listenersOfDropdown4s = @json($listeners4);
    filtersOfDropdown4s = @json($filters4);

</script>

<div class="px-4">
    <x-controls.workflow403-checker action="{{$action}}" type="{{$type}}" status="{{$status}}"/>
    <x-controls.header-alert-validation :strProps="$props" />
    @if(App\Utils\Support\CurrentUser::isAdmin())
        @if($status && !($action === 'create'))
            <div class="w-full mb-8 p-2 bg-white rounded-lg  dark:bg-gray-800">
                <x-renderer.card title="Test Status" >
                    <div class="mb-3">
                        @foreach($statuses as $key => $value)
                            <span class="bg-{{$value['color']}}-{{$value['color_index']}} whitespace-nowrap rounded hover:bg-blue-400 font-medium text-xs px-2 py-1.5 leading-tight mx-1">
                                <a href="{{route($type.'.edit',$id)}}?status={{$value['name']}}">{{$value['title']}}</a>
                            </span>
                        @endforeach
                    </div>
                </x-renderer.card>
                <x-renderer.card title="Accessible" >
                    <div class="mb-3">
                        @foreach($statuses[$status]['capability-roles'] as $value)
                            <span><x-renderer.tag color="gray" rounded="rounded" class="ml-1">{{$value}}</x-renderer.tag></span>
                        @endforeach
                    </div>
                </x-renderer.card>
            </div>
        @endif
    @endif
    <form class="w-full mb-8 bg-white rounded-lg  dark:bg-gray-800" id="form-upload" method="POST" enctype="multipart/form-data" action="{{ route($action === "create" ? $editType.'.store': $editType.'.update', $action === "create" ? '' : $id )}} ">
        @csrf        
        <input name="tableNames[table00]" value="(the_form)" type='hidden' /> {{-- This line is required for updating  --}}
        <div class=" grid grid-cols-12 px-4">
            @method($action === "create" ? 'POST' : 'PUT')
            @foreach($result as $propKey => $prop)
            @php
            if ($action === "create" && $prop['control'] === 'relationship_renderer') continue;
            $prop ? extract($prop) : null;
            @endphp
            @if($prop)
            <div class='col-span-{{$col_span}} grid'>
                <div class='grid grid-row-1'>
                    <div class='grid grid-cols-12 items-center content-start {{$hiddenRow}} '>
                        @if($columnType === 'static')
                        <div class='col-span-12 text-left'>
                            @switch($control)
                                @case('z_page_break')
                                <x-renderer.page-break />
                                @case('z_h1')
                                <x-renderer.heading title="{{$title}}" level=1 align="{{$align}}">{{$label}}</x-renderer.heading>
                                @break
                                @case('z_h2')
                                <x-renderer.heading title="{{$title}}" level=2 align="{{$align}}">{{$label}}</x-renderer.heading>
                                @break
                                @case('z_h3')
                                <x-renderer.heading title="{{$title}}" level=3 align="{{$align}}">{{$label}}</x-renderer.heading>
                                @break
                                @case('z_h4')
                                <x-renderer.heading title="{{$title}}" level=4 align="{{$align}}">{{$label}}</x-renderer.heading>
                                @break
                                @case('z_h5')
                                <x-renderer.heading title="{{$title}}" level=5 align="{{$align}}">{{$label}}</x-renderer.heading>
                                @break
                                @case('z_h6_base')
                                <x-renderer.heading title="{{$title}}" align="{{$align}}">{{$label}}</x-renderer.heading>
                                @break
                                @case('z_divider')
                                <x-renderer.divider />
                                @break

                                @default
                                <x-feedback.alert type="warning" title="{{$title}}" message="The control [{{$control}}] is not available" />
                                @break
                            @endswitch
                        </div>
                        @else
                        <div class='col-start-1 {{$classColSpanLabel}}  {{$prop['new_line'] === 'true' ? "text-left" : "text-right" }} '>
                            @if(!$hiddenLabel)
                            <label class='text-gray-700 dark:text-gray-300  px-3 block text-base' title='{{$title}}'>
                                {{$label}}
                                @endif
                                {!!$isRequired ? "<span class='text-red-400'>*</span>" : "" !!}
                                @if(!$hiddenLabel)
                                <br />
                                @endif
                                <span class="flex justify-end">{!!$iconJson!!}</span>
                                @if(!$hiddenLabel)
                                <i>{{$labelExtra}}</i>
                            </label>
                            @endif
                        </div>
                        <div class="{{$classColStart}} {{$classColSpanControl}} py-2 text-left">
                            @if (is_null($control))
                            <h2 class="text-red-400">{{"Control of this $columnName has not been set"}}</h2>
                            @endif
                            {{-- Invisible anchor for scrolling when users click on validation fail message --}}
                            <strong class="scroll-mt-20 snap-start" id="scroll-{{$columnName}}"></strong>
                            @switch ($control)
                                @case('id')
                                @case('doc_id')
                                <x-controls.id name={{$columnName}} value="{{$action === 'edit' ? $value : 'to be generated'}}" />
                                @break
                                @case('hyperlink')
                                @php $placeholder="https://www.google.com"; @endphp
                                @case('text')
                                @case('thumbnail')
                                <x-controls.text name={{$columnName}} value={{$value}} placeholder="{{$placeholder}}" readOnly={{$readOnly}} />
                                <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                @break
                                @case('number')
                                <x-controls.number name={{$columnName}} value={{$value}} readOnly={{$readOnly}}/>
                                <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                @break
                                @case('textarea')
                                <x-controls.textarea name={{$columnName}} :value="$value" colType={{$columnType}} placeholder="{{$placeholder}}" readOnly={{$readOnly}}/>
                                <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                @break
                                @case('toggle')
                                <x-controls.toggle name={{$columnName}} value={{$value}} readOnly={{$readOnly}} />
                                <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                @break
                                @case('status')
                                <x-controls.control-status value={{$status}} name={{$columnName}} modelPath={{$modelPath}} readOnly={{$readOnly}} />
                                @break
                                @case ('dropdown')
                                <x-controls.has-data-source.dropdown2 type={{$type}} name={{$columnName}} selected={{$value}} readOnly={{$readOnly}} />
                                @break
                                @case ('radio')
                                <x-controls.has-data-source.radio-or-checkbox type={{$type}} name={{$columnName}} selected={{$value}} readOnly={{$readOnly}} />
                                @break
                                @case ('dropdown_multi')
                                <x-controls.has-data-source.dropdown2 type={{$type}} name={{$columnName}} selected={{$value}} readOnly={{$readOnly}} multiple={{true}} />
                                @break
                                @case('checkbox')
                                <x-controls.has-data-source.radio-or-checkbox type={{$type}} name={{$columnName}} selected={{$value}} readOnly={{$readOnly}} multiple={{true}}/>
                                @break
                                @case('picker_time')
                                <x-controls.text name={{$columnName}} value={{$value}} readOnly={{$readOnly}} placeholder="HH:MM" icon="fa-duotone fa-clock" />
                                <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                @break
                                @case('picker_datetime')
                                <x-controls.text name={{$columnName}} value={{$value}} readOnly={{$readOnly}} placeholder="DD/MM/YYYY HH:MM" icon="fa-solid fa-calendar-day" />
                                <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                @break
                                @case('picker_date')
                                <x-controls.date-picker3 name={{$columnName}} value={{$value}} readOnly={{$readOnly}} dateTimeType="{{$control}}"/>
                                <x-controls.localtime id={{$id}} control={{$control}} colName={{$columnName}} modelPath={{$modelPath}} label={{$label}} />
                                <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                @break
                                @case('picker_week')
                                <x-controls.text name={{$columnName}} value={{$value}} readOnly={{$readOnly}} placeholder="W01/YYYY" icon="fa-solid fa-calendar-day" />
                                <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                @break
                                @case('picker_month')
                                <x-controls.text name={{$columnName}} value={{$value}} readOnly={{$readOnly}} placeholder="MM/YYYY" icon="fa-solid fa-calendar-day" />
                                <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                @break
                                @case('picker_quarter')
                                <x-controls.text name={{$columnName}} value={{$value}} readOnly={{$readOnly}} placeholder="Q1/YYYY" icon="fa-solid fa-calendar-day" />
                                <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                @break
                                @case('picker_year')
                                <x-controls.text name={{$columnName}} value={{$value}} readOnly={{$readOnly}} placeholder="YYYY" icon="fa-solid fa-calendar-day" />
                                <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                @break
                                @case('attachment')
                                <x-renderer.attachment2 name={{$columnName}} value={{$value}} readOnly={{$readOnly}} />
                                @break
                                @case('comment')
                                <x-controls.comment-group2 :item="$item" id={{$id}} type={{$type}} name={{$columnName}} readOnly={{$readOnly}} />
                                {{-- <x-controls.comment-group id={{$id}} type={{$type}} colName={{$columnName}} label={{$label}} colSpan={{$col_span}} /> --}}
                                @break

                                @case('relationship_renderer')
                                <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                <x-controls.relationship-renderer id={{$id}} type={{$type}} colName={{$columnName}} modelPath={{$modelPath}} />
                                @break

                                @case('parent_type')
                                <x-renderer.parent_type type={{$type}} name={{$columnName}} selected="{{$value}}" readOnly={{$readOnly}}/>
                                @break
                                @case('parent_id')
                                <x-renderer.parent_id type={{$type}} name={{$columnName}} selected="{{$value}}" readOnly={{$readOnly}}/>
                                @break

                                @case('parent_link')
                                <x-feedback.alert type="warning" title="Warning" message="{{$control}} suppose to show in View All screen only, please do not show in Edit screen." />
                                @break
                                                    {{-- @case('realtime')
                                <x-renderer.realtime :item="$item" name={{$columnName}} realtimeType={{$realtimeType}} realtimeFn={{$realtimeFn}} status={{$status}} value={{$value}} />
                                @break --}}

                                @default
                                <x-feedback.alert type="warning" title="Control" message="Unknown how to render [{{$control}}/{{$columnName}}]" />
                                @break
                            @endswitch
                            {{$controlExtra}}
                        </div>
                        @endif
                    </div>

                </div>
            </div>
            @endif
            @endforeach
        </div>
        @foreach($propsIntermediate as $key => $props)
        @php
            $resultIntermediate = App\Utils\Support\WorkflowFields::parseFields($props, $values, $defaultValues,$status,$type);
        @endphp
        <template x-if="isIntermediateOpen['{{$key}}']">
            <div tabindex="-1" class="fixed sm:p-0 md:p-0 top-0 left-0 right-0 z-50 lg:p-4 h-full bg-gray-100 dark:bg-slate-400 dark:bg-opacity-70 bg-opacity-70 justify-center items-center flex" aria-hidden="true" @keydown.escape="closeIntermediate('{{$key}}')">
                <div class="relative sm:mx-0 md:mx-5  w-full lg:mx-10 xl:mx-16 2xl:mx-20 h-auto md:h-auto sm:h-auto" @click.away="">
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                        <!-- Modal header -->
                        <div class="items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                            <div class="flex">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    Intermediate
                                </h3>
                                <button type="button" @click="closeIntermediate('{{$key}}')" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="large-modal">
                                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                            </div>
                        </div>
                        <!-- Modal body -->
                            <div class='overflow-y-scroll p-4'>
                                <div class="grid grid-cols-12 px-4">
                                    @foreach($resultIntermediate as $prop)
                                        @php
                                        if ($action === "create" && $prop['control'] === 'relationship_renderer') continue;
                                        $prop ? extract($prop) : null;
                                        @endphp
                                        @if($prop)
                                        <div class='col-span-{{$col_span}} grid'>
                                            <div class='grid grid-row-1'>
                                                <div class='grid grid-cols-12 items-center content-start {{$hiddenRow}} '>
                                                    @if($columnType === 'static')
                                                    <div class='col-span-12 text-left'>
                                                        @switch($control)
                                                            @case('z_page_break')
                                                            <x-renderer.page-break />
                                                            @case('z_h1')
                                                            <x-renderer.heading title="{{$title}}" level=1 align="{{$align}}">{{$label}}</x-renderer.heading>
                                                            @break
                                                            @case('z_h2')
                                                            <x-renderer.heading title="{{$title}}" level=2 align="{{$align}}">{{$label}}</x-renderer.heading>
                                                            @break
                                                            @case('z_h3')
                                                            <x-renderer.heading title="{{$title}}" level=3 align="{{$align}}">{{$label}}</x-renderer.heading>
                                                            @break
                                                            @case('z_h4')
                                                            <x-renderer.heading title="{{$title}}" level=4 align="{{$align}}">{{$label}}</x-renderer.heading>
                                                            @break
                                                            @case('z_h5')
                                                            <x-renderer.heading title="{{$title}}" level=5 align="{{$align}}">{{$label}}</x-renderer.heading>
                                                            @break
                                                            @case('z_h6_base')
                                                            <x-renderer.heading title="{{$title}}" align="{{$align}}">{{$label}}</x-renderer.heading>
                                                            @break
                                                            @case('z_divider')
                                                            <x-renderer.divider />
                                                            @break
                            
                                                            @default
                                                            <x-feedback.alert type="warning" title="{{$title}}" message="The control [{{$control}}] is not available" />
                                                            @break
                                                        @endswitch
                                                    </div>
                                                    @else
                                                    <div class='col-start-1 {{$classColSpanLabel}}  {{$prop['new_line'] === 'true' ? "text-left" : "text-right" }} '>
                                                        @if(!$hiddenLabel)
                                                        <label class='text-gray-700 dark:text-gray-300  px-3 block text-base' title='{{$title}}'>
                                                            {{$label}}
                                                            @endif
                                                            {!!$isRequired ? "<span class='text-red-400'>*</span>" : "" !!}
                                                            @if(!$hiddenLabel)
                                                            <br />
                                                            @endif
                                                            <span class="flex justify-end">{!!$iconJson!!}</span>
                                                            @if(!$hiddenLabel)
                                                            <i>{{$labelExtra}}</i>
                                                        </label>
                                                        @endif
                                                    </div>
                                                    <div class="{{$classColStart}} {{$classColSpanControl}} py-2 text-left">
                                                        @if (is_null($control))
                                                        <h2 class="text-red-400">{{"Control of this $columnName has not been set"}}</h2>
                                                        @endif
                                                        <strong class="scroll-mt-20 snap-start" id="scroll-{{$columnName}}"></strong>
                                                        @switch ($control)
                                                            @case('id')
                                                            @case('doc_id')
                                                            <x-controls.id name={{$columnName}} value="{{$action === 'edit' ? $value : 'to be generated'}}" />
                                                            @break
                                                            @case('hyperlink')
                                                            @php $placeholder="https://www.google.com"; @endphp
                                                            @case('text')
                                                            @case('thumbnail')
                                                            <x-controls.text name={{$columnName}} value={{$value}} placeholder="{{$placeholder}}" readOnly={{$readOnly}} />
                                                            <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                                            @break
                                                            @case('number')
                                                            <x-controls.number name={{$columnName}} value={{$value}} readOnly={{$readOnly}}/>
                                                            <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                                            @break
                                                            @case('textarea')
                                                            <x-controls.textarea name={{$columnName}} :value="$value" colType={{$columnType}} placeholder="{{$placeholder}}" readOnly={{$readOnly}}/>
                                                            <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                                            @break
                                                            @case('toggle')
                                                            <x-controls.toggle name={{$columnName}} value={{$value}} readOnly={{$readOnly}} />
                                                            <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                                            @break
                                                            @case ('dropdown')
                                                            <x-controls.has-data-source.dropdown2 type={{$type}} name={{$columnName}} selected={{$value}} readOnly={{$readOnly}} />
                                                            @break
                                                            @case ('radio')
                                                            <x-controls.has-data-source.radio-or-checkbox type={{$type}} name={{$columnName}} selected={{$value}} readOnly={{$readOnly}} />
                                                            @break
                                                            @case ('dropdown_multi')
                                                            <x-controls.has-data-source.dropdown2 type={{$type}} name={{$columnName}} selected={{$value}} readOnly={{$readOnly}} multiple={{true}} />
                                                            @break
                                                            @case('checkbox')
                                                            <x-controls.has-data-source.radio-or-checkbox type={{$type}} name={{$columnName}} selected={{$value}} readOnly={{$readOnly}} multiple={{true}}/>
                                                            @break
                                                            @case('picker_time')
                                                            <x-controls.text name={{$columnName}} value={{$value}} readOnly={{$readOnly}} placeholder="HH:MM" icon="fa-duotone fa-clock" />
                                                            <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                                            @break
                                                            @case('picker_datetime')
                                                            <x-controls.text name={{$columnName}} value={{$value}} readOnly={{$readOnly}} placeholder="DD/MM/YYYY HH:MM" icon="fa-solid fa-calendar-day" />
                                                            <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                                            @break
                                                            @case('picker_date')
                                                            <x-controls.date-picker3 name={{$columnName}} value={{$value}} readOnly={{$readOnly}} dateTimeType="{{$control}}"/>
                                                            <x-controls.localtime id={{$id}} control={{$control}} colName={{$columnName}} modelPath={{$modelPath}} label={{$label}} />
                                                            <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                                            @break
                                                            @case('picker_week')
                                                            <x-controls.text name={{$columnName}} value={{$value}} readOnly={{$readOnly}} placeholder="W01/YYYY" icon="fa-solid fa-calendar-day" />
                                                            <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                                            @break
                                                            @case('picker_month')
                                                            <x-controls.text name={{$columnName}} value={{$value}} readOnly={{$readOnly}} placeholder="MM/YYYY" icon="fa-solid fa-calendar-day" />
                                                            <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                                            @break
                                                            @case('picker_quarter')
                                                            <x-controls.text name={{$columnName}} value={{$value}} readOnly={{$readOnly}} placeholder="Q1/YYYY" icon="fa-solid fa-calendar-day" />
                                                            <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                                            @break
                                                            @case('picker_year')
                                                            <x-controls.text name={{$columnName}} value={{$value}} readOnly={{$readOnly}} placeholder="YYYY" icon="fa-solid fa-calendar-day" />
                                                            <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                                            @break
                                                            @case('attachment')
                                                            <x-renderer.attachment2 name={{$columnName}} value={{$value}} readOnly={{$readOnly}} />
                                                            @break
                                                            @case('comment')
                                                            <x-controls.comment-group2 :item="$item" id={{$id}} type={{$type}} name={{$columnName}} readOnly={{$readOnly}} />
                                                            @break
                                                            @case('relationship_renderer')
                                                            <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                                            <x-controls.relationship-renderer id={{$id}} type={{$type}} colName={{$columnName}} modelPath={{$modelPath}} />
                                                            @break
                                                            @case('parent_type')
                                                            <x-renderer.parent_type type={{$type}} name={{$columnName}} selected="{{$value}}" readOnly={{$readOnly}}/>
                                                            @break
                                                            @case('parent_id')
                                                            <x-renderer.parent_id type={{$type}} name={{$columnName}} selected="{{$value}}" readOnly={{$readOnly}}/>
                                                            @break
                                                            @case('parent_link')
                                                            <x-feedback.alert type="warning" title="Warning" message="{{$control}} suppose to show in View All screen only, please do not show in Edit screen." />
                                                            @break
                                                            @default
                                                            <x-feedback.alert type="warning" title="Control" message="Unknown how to render [{{$control}}/{{$columnName}}]" />
                                                            @break
                                                        @endswitch
                                                        {{$controlExtra}}
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            <div class="flex items-center justify-end rounded-b border-t border-solid border-slate-200 dark:border-gray-600 p-2">
                                <button type="submit" class="px-2.5 py-2  inline-block  font-medium text-sm leading-tight rounded focus:ring-0 transition duration-150 ease-in-out bg-purple-600 text-white shadow-md hover:bg-purple-700 hover:shadow-lg focus:bg-purple-700 focus:shadow-lg focus:outline-none active:bg-purple-800 active:shadow-lg">
                                        Next -> (to {{$actionButtons[$key]['label']}})
                                </button>
                            </div>                
                    </div>
                </div>
            </div>
          </template>
        @endforeach
        
        <div class="flex justify-end dark:bg-gray-800 px-5">
            <div class="my-5">
                @if($buttonSave)
                <button type="submit" class="px-2.5 py-2  inline-block  font-medium text-sm leading-tight rounded focus:ring-0 transition duration-150 ease-in-out bg-blue-600 text-white shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none active:bg-blue-800 active:shadow-lg">
                    <i class="fa-solid fa-floppy-disk mr-2"></i>Save</button>
                @endif
                @foreach($actionButtons as $key => $button)
                @php
                    $isCheck = !isset($propsIntermediate[$key]) || empty($propsIntermediate[$key]);
                @endphp
                    <button {{$isCheck ? 'type=submit '. '@click=changeStatus("'.$key .'")' : 'type=button '. '@click=toggleIntermediate("'.$key .'")' }} class="px-2.5 py-2  inline-block  font-medium text-sm leading-tight rounded focus:ring-0 transition duration-150 ease-in-out bg-purple-600 text-white shadow-md hover:bg-purple-700 hover:shadow-lg focus:bg-purple-700 focus:shadow-lg focus:outline-none active:bg-purple-800 active:shadow-lg">
                        Next -> (to {{$button['label']}})
                    </button>
                @endforeach
            </div>
        </div>

    </form>
</div>

@endsection
