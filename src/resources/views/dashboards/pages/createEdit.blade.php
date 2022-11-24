@extends('layouts.app')

@section("title", Str::pretty($action))

@section('content')

@php
$editType = Str::plural($type);
$labelValidation = "";
$id = $action === "edit" ? $values->id : "";
@endphp

<x-controls.headeralertvalidation :strProps="$props" />
<form class="w-full p-4 z-0 px-4 py-3 text-center mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800" id="form-upload" method="POST" enctype="multipart/form-data" action="{{ route($action === "create" ? $editType.'_addnew.store': $editType.'_edit.update', $action === "create" ? 0 : $id )}} ">
    @csrf
    <div class="flex flex-col grid-cols-12">
        @method($action === "create" ? 'POST' : 'PUT')
        @php
        $timeControls = ['picker_time','picker_date','picker_month','picker_week','picker_quarter','picker_year','picker_datetime'];
        $valColumnNames = ['date_of_birth', 'first_date', 'last_date', 'created_at', 'updated_at'];

        @endphp
        @foreach($props as $key => $value)
        @php
        $label = $value['label'];
        $col_span = $value['col_span'];
        $column_name = $value['column_name'];
        $column_type = $value['column_type'];
        $control = $value['control'];
        $value_column_name = $action === "edit" ? $values->{$column_name} :'';
        // dd($values->getAttributes()['settings']);

        $col_span = $value['col_span'];
        $hiddenRow = $props[$key]['hidden_edit'] === 'true' ? "hidden":"";

        $isRequired = in_array("required", explode("|",$value['validation']));
        $iconJson = $column_type === 'json' ? App\Utils\ConstantSVG::ICON_SVG : "";
        @endphp

        <div class='col-span-{{$col_span}}'>
            <div class='grid grid-row-1 gap-3'>
                <div class='grid grid-cols-12 items-center {{$hiddenRow}}'>
                    <div class='col-start-1 col-span-{{24/$col_span}} {{$value['new_line'] ? "col-span-12 text-left" : "text-right" }} '>
                        <label class='text-gray-700 dark:text-gray-400  px-3 block text-base' title='{{$column_name}} / {{$control}}'>{{$label}}
                            {!!$isRequired ? "<span class='text-red-400'>*</span>" : "" !!}
                            <br />
                            <span class="items-end">
                                {!!$iconJson!!}
                            </span>
                    </div>
                    <div class='col-start-{{24/$col_span + 1}} col-span-10  {{$value['new_line'] ? "col-span-12" : "" }}   py-2 text-left'>
                        @if (is_null($control))
                        <h2 class="text-red-400">{{"Control of this $column_name has not been set"}}</h2>
                        @endif

                        <!-- Invisible anchor for scrolling when users click on validation fail message -->
                        <strong class="scroll-mt-20 snap-start" id="{{$column_name}}"></strong>

                        @switch ($control)
                        @case($timeControls[0])
                        @case($timeControls[1])
                        @case($timeControls[2])
                        @case($timeControls[3])
                        @case($timeControls[4])
                        @case($timeControls[5])
                        @case($timeControls[6])
                        <x-controls.text colName={{$column_name}} valColName={{$value_column_name}} action={{$action}} :strTimeControl="$timeControls" control={{$control}} labelName={{$label}} />
                        @break

                        @case('text')
                        <x-controls.text colName={{$column_name}} valColName={{$value_column_name}} action={{$action}} :strTimeControl="$timeControls" control={{$control}} labelName={{$label}} />
                        @break

                        @case('id')
                        <x-controls.id colName={{$column_name}} valColName={{$value_column_name}} action={{$action}} :strTimeControl="$timeControls" control={{$control}} labelName={{$label}} />
                        @break

                        @case('textarea')
                        <x-controls.textarea colName={{$column_name}} colType={{$column_type}} :valColName="$value_column_name" action={{$action}} control={{$control}} labelName={{$label}} />
                        @break

                        @case ('dropdown')
                        <x-controls.dropdown id={{$id}} colName={{$column_name}} type={{$type}} tablePath={{$tablePath}} action={{$action}} labelName={{$label}} />
                        @break

                        @case ('radio')
                        <x-controls.radio id={{$id}} colName={{$column_name}} tablePath={{$tablePath}} action={{$action}} labelName={{$label}} />
                        @break

                        @case ('dropdown_multi')
                        <x-controls.dropdownmulti colName={{$column_name}} :idItems="$idItems" action={{$action}} tablePath={{$tablePath}} labelName={{$label}} type={{$type}} />
                        @break

                        @case('attachment')
                        <x-controls.uploadfiles id={{$id}} colName={{$column_name}} action={{$action}} tablePath={{$tablePath}} labelName={{$label}} />
                        @break

                        @case('switch')
                        <x-controls.toggle id={{$id}} colName={{$column_name}} valColName={{$value_column_name}} labelName={{$label}} />
                        @break

                        @case('checkbox')
                        <x-controls.checkbox id={{$id}} colName={{$column_name}} :idItems="$idItems" action={{$action}} tablePath={{$tablePath}} labelName={{$label}} type={{$type}} />
                        @break

                        @case('number')
                        <x-controls.number colName={{$column_name}} valColName={{$value_column_name}} action={{$action}} :strTimeControl="$timeControls" control={{$control}} labelName={{$label}} />
                        @break

                        @default
                        <x-feedback.alert type="warning" title="Control" message="[{{$control}}] is not available" />
                        @break
                        @endswitch

                        @switch ($action)
                        @case('edit')
                        <x-controls.localtime tablePath={{$tablePath}} :timeControls="$timeControls" :valColumnNames="$valColumnNames" id={{$id}} control={{$control}} colName={{$column_name}} labelName={{$label}} />
                        @break
                        @endswitch
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>
    <div class="flex justify-left border-t-2 dark:bg-gray-800  px-5">
        @switch($action)
        @case('edit')
        <button type="submit" class="mt-4 focus:shadow-outline rounded bg-emerald-500 py-2 px-4 font-bold text-white shadow hover:bg-purple-400 focus:outline-none" type="button">
            Update
        </button>
        @break
        @case('create')
        <button type="submit" class="mt-4  shadow-lg focus:shadow-outline  rounded bg-emerald-500 py-2 px-4 font-bold text-white hover:bg-purple-400 focus:outline-none" type="button">
            Create
        </button>
        @break
        @default
        <span>Unknown action "{{$action}}"</span>
        @break
        @endswitch
    </div>
</form>
@endsection
