@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $title )

@section('content')

@php
$editType = Str::plural($type);
$labelValidation = "";
$id = $action === "edit" ? $values->id : "";
@endphp

{{-- HERE {{mb_strlen(serialize((array)$listenerDataSource), '8bit');}} bytes --}}
<script>
    k = @json($listenerDataSource);

    listenersOfDropdown2 = @json($listeners2);
    filtersOfDropdown2 = @json($filters2);
    
    listenersOfDropdown4s = @json($listeners4);
    filtersOfDropdown4s = @json($filters4);

</script>

<div class="px-4">
    <x-controls.header-alert-validation :strProps="$props" />
    <form class="w-full mb-8 bg-white rounded-lg  dark:bg-gray-800" id="form-upload" method="POST" enctype="multipart/form-data" action="{{ route($action === "create" ? $editType.'.store': $editType.'.update', $action === "create" ? 0 : $id )}} ">
        @csrf        
        <input name="tableNames[table00]" value="(the_form)" type='hidden' /> {{-- This line is required for updating  --}}
        <div class=" grid grid-cols-12 px-4">
            @method($action === "create" ? 'POST' : 'PUT')
            @php
            $timeControls = ['picker_time','picker_date','picker_month','picker_week','picker_quarter','picker_year','picker_datetime'];
            @endphp
            @foreach($props as $key => $val)
            @php
            if ($action === "create" && $val['control'] === 'relationship_renderer') continue;
            $defaultValue = $defaultValues[$key] ?? [];

            $label = $val['label'];
            $columnName = $val['column_name'];
            $columnType = $val['column_type'];
            $align = $val['align'] ?? 'left';
            $control = $val['control'];

            $colSpan = $val['col_span'];
            $value = $values->{$columnName} ?? '';
            $title = $columnName." / ".$control ;
            $col_span = $val['col_span'] === '' ? 12 : $val['col_span'] * 1;
            $hiddenRow = $props[$key]['hidden_edit'] === 'true' ? "hidden":"";

            $isRequired = in_array("required", explode("|", $defaultValue['validation'] ?? ""));
            $iconJson = $columnType === 'json' ?'<i title="JSON format" class="fa-duotone fa-brackets-curly"></i>' : "";
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
                            <x-feedback.alert type="warning" title="{{$title}}" message="[{{$control}}] is not available" />
                            @break
                            @endswitch
                        </div>
                        @else
                        <div class='col-span-{{24/$col_span}} col-start-1 {{$val['new_line'] === 'true' ? "col-span-12 text-left" : "text-right" }} '>
                            <label class='text-gray-700 dark:text-gray-300  px-3 block text-base' title='{{$title}}'>{{$label}}
                                {!!$isRequired ? "<span class='text-red-400'>*</span>" : "" !!}
                                <br />
                                <span class="flex justify-end">
                                    {!!$iconJson!!}
                                </span>
                            </label>
                        </div>
                        <div class='col-start-{{24/$col_span+1}} {{$val['new_line'] === 'true' ? "col-span-12" : "col-span-".(12 - 24/$col_span)}} py-2 text-left'>
                            @if (is_null($control))
                            <h2 class="text-red-400">{{"Control of this $columnName has not been set"}}</h2>
                            @endif

                            {{-- Invisible anchor for scrolling when users click on validation fail message --}}
                            <strong class="scroll-mt-20 snap-start" id="scroll-{{$columnName}}">
                                {{-- #{{$columnName}} --}}
                            </strong>
                            @switch ($control)
                            @case($timeControls[0])
                            {{-- <x-controls.time-picker2 :name="$columnName" :value="$value"/> --}}
                            <x-controls.date-time name={{$columnName}} value={{$value}} control="{{$control}}" />
                            @break
                            @case($timeControls[1])
                            @case($timeControls[2])
                            @case($timeControls[3])
                            @case($timeControls[4])
                            @case($timeControls[5])
                            @case($timeControls[6])
                            {{-- <x-controls.date-time name={{$columnName}} value={{$value}} control="{{$control}}" /> --}}
                            <x-controls.date-picker3 name={{$columnName}} value={{$value}} control="{{$control}}" />
                            <x-controls.localtime id={{$id}} control={{$control}} colName={{$columnName}} modelPath={{$modelPath}} :timeControls="$timeControls" label={{$label}} />
                            <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                            @break
                            @case('id')
                            <x-controls.id name={{$columnName}} value="{{$action === 'edit' ? $value : 'to be generated'}}" />
                            @break
                            @case('hyperlink')
                            @case('text')
                            @case('thumbnail')
                            <x-controls.text name={{$columnName}} value={{$value}} />
                            <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                            @break
                            @case('number')
                            <x-controls.number name={{$columnName}} value={{$value}} />
                            <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                            @break
                            @case('textarea')
                            <x-controls.textarea name={{$columnName}} :value="$value" colType={{$columnType}} />
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
                            <x-controls.has-data-source.dropdown2 type={{$type}} name={{$columnName}} selected={{$value}} />
                            @break
                            @case ('radio')
                            <x-controls.has-data-source.radio-or-checkbox type={{$type}} name={{$columnName}} selected={{$value}} />
                            @break
                            @case ('dropdown_multi')
                            <x-controls.has-data-source.dropdown2 type={{$type}} name={{$columnName}} selected={{$value}} multiple={{true}} />
                            @break
                            @case('checkbox')
                            <x-controls.has-data-source.radio-or-checkbox type={{$type}} name={{$columnName}} selected={{$value}} multiple={{true}}/>
                            @break

                            @case('attachment')
                            <x-renderer.attachment2 name={{$columnName}} value={{$value}} />

                            @break
                            @case('comment')
                            <x-controls.comment-group id={{$id}} type={{$type}} colName={{$columnName}} label={{$label}} colSpan={{$col_span}} />
                            @break

                            @case('relationship_renderer')
                            {{-- @if($action == 'create') --}}
                            {{-- <x-feedback.alert type="info" title="Info" message="Please create this document to show this control." /> --}}
                            {{-- @else --}}
                            <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                            <x-controls.relationship-renderer id={{$id}} type={{$type}} colName={{$columnName}} modelPath={{$modelPath}} />
                            {{-- @endif --}}
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

                            @default
                            <x-feedback.alert type="warning" title="Control" message="Unknown how to render [{{$control}}/{{$columnName}}]" />
                            @break
                            @endswitch
                        </div>
                        @endif
                    </div>

                </div>
            </div>
            @endforeach
        </div>
        <div class="flex justify-left border-t-2 dark:bg-gray-800 px-5">
            <button type="submit" class="m-4 focus:shadow-outline rounded bg-emerald-500 py-2 px-4 font-bold text-white hover:bg-purple-400 focus:outline-none">
                @switch($action)
                @case('edit')
                Update
                @break
                @case('create')
                Create
                @break
                @default
                <span>Unknown action "{{$action}}"</span>
                @break
                @endswitch
            </button>
        </div>
    </form>
</div>
@endsection
