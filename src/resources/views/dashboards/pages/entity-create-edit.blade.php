@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $title )

@section('content')

@php
$editType = Str::plural($type);
$id = $action === "edit" ? $values->id : "";
$status = $values->status ?? null;
@endphp

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
    <form class="w-full mb-8 bg-white rounded-lg  dark:bg-gray-800" id="form-upload" method="POST" enctype="multipart/form-data" action="{{ route($action === "create" ? $editType.'.store': $editType.'.update', $action === "create" ? '' : $id )}} ">
        @csrf        
        <input name="tableNames[table00]" value="(the_form)" type='hidden' /> {{-- This line is required for updating  --}}
        <div class=" grid grid-cols-12 px-4">
            @method($action === "create" ? 'POST' : 'PUT')
            
            @foreach($props as $propKey => $prop)
            @php
            if ($action === "create" && $prop['control'] === 'relationship_renderer') continue;
            extract(App\Utils\Support\WorkflowFields::parseFields($propKey, $prop, $values, $defaultValues));
            @endphp
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
                                <x-controls.text name={{$columnName}} value={{$value}} placeholder="{{$placeholder}}" />
                                <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                @break
                                @case('number')
                                <x-controls.number name={{$columnName}} value={{$value}} />
                                <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                @break
                                @case('textarea')
                                <x-controls.textarea name={{$columnName}} :value="$value" colType={{$columnType}} placeholder="{{$placeholder}}"/>
                                <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                @break
                                @case('toggle')
                                <x-controls.toggle name={{$columnName}} value={{$value}} />
                                <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                @break
                                @case('status')
                                <x-controls.control-status value={{$value}} name={{$columnName}} modelPath={{$modelPath}} />
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
                                <x-controls.text name={{$columnName}} value={{$value}} placeholder="HH:MM" icon="fa-duotone fa-clock" />
                                <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                @break
                                @case('picker_datetime')
                                <x-controls.text name={{$columnName}} value={{$value}} placeholder="DD/MM/YYYY HH:MM" icon="fa-solid fa-calendar-day" />
                                <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                @break
                                @case('picker_date')
                                <x-controls.date-picker3 name={{$columnName}} value={{$value}} dateTimeType="{{$control}}"/>
                                <x-controls.localtime id={{$id}} control={{$control}} colName={{$columnName}} modelPath={{$modelPath}} label={{$label}} />
                                <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                @break
                                @case('picker_week')
                                <x-controls.text name={{$columnName}} value={{$value}} placeholder="W01/YYYY" icon="fa-solid fa-calendar-day" />
                                <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                @break
                                @case('picker_month')
                                <x-controls.text name={{$columnName}} value={{$value}} placeholder="MM/YYYY" icon="fa-solid fa-calendar-day" />
                                <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                @break
                                @case('picker_quarter')
                                <x-controls.text name={{$columnName}} value={{$value}} placeholder="Q01/YYYY" icon="fa-solid fa-calendar-day" />
                                <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                @break
                                @case('picker_year')
                                <x-controls.text name={{$columnName}} value={{$value}} placeholder="YYYY" icon="fa-solid fa-calendar-day" />
                                <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                @break
                                @case('attachment')
                                <x-renderer.attachment2 name={{$columnName}} value={{$value}} />
                                @break
                                @case('comment')
                                <x-controls.comment-group2 :item="$item" id={{$id}} type={{$type}} name={{$columnName}} />
                                {{-- <x-controls.comment-group id={{$id}} type={{$type}} colName={{$columnName}} label={{$label}} colSpan={{$col_span}} /> --}}
                                @break

                                @case('relationship_renderer')
                                <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                <x-controls.relationship-renderer id={{$id}} type={{$type}} colName={{$columnName}} modelPath={{$modelPath}} />
                                @break

                                @case('parent_type')
                                <x-renderer.parent_type type={{$type}} name={{$columnName}} selected="{{$value}}"/>
                                @break
                                @case('parent_id')
                                <x-renderer.parent_id type={{$type}} name={{$columnName}} selected="{{$value}}"/>
                                @break

                                @case('parent_link')
                                <x-feedback.alert type="warning" title="Warning" message="{{$control}} suppose to show in View All screen only, please do not show in Edit screen." />
                                @break

                                @case('realtime')
                                <x-renderer.realtime :item="$item" name={{$columnName}} realtimeType={{$realtimeType}} realtimeFn={{$realtimeFn}} status={{$status}} value={{$value}} />
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
            @endforeach
        </div>
        <div class="flex justify-left dark:bg-gray-800 px-5">
            <div class="my-5">
                <x-controls.action-buttons action="{{$action}}" />
            </div>
        </div>
    </form>
</div>
@endsection
