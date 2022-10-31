@extends('layouts.app')
@section('content')

{{-- {{dd($warningValidator)}} --}}

<div class="focus:shadow-outline-purple my-4 flex items-center justify-between rounded-lg bg-purple-600 p-3 text-base font-semibold text-purple-100 shadow-md focus:outline-none">
    <div class="flex items-center">
        <svg class="mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
            </path>
        </svg>
        @switch($action)
        @case('edit')
        <span>Update a {{$type}}</span>
        @break
        @case('create')
        <span>Create a {{$type}}</span>
        @break
        @endswitch
    </div>
</div>
<div class="grid grid-rows-1 bg-gray-100">


    <div class="mx-auto flex flex-col min-h-screen items-center py-5">
        {{-- {{dd($type)}} --}}
        @php
        $editType = Str::plural($type);
        // dd(isset($values), $values);
        $id = $action === "edit" ? $values->id : "";
        $idAvatar = isset($values['avatar']) ? $values['avatar']: "";
        @endphp
        @include('components.render.alertValidations')
        <form class="rounded-md bg-gray-50 p-4 z-0" id="form-upload" method="POST" enctype="multipart/form-data" action="{{ route($action === "create" ? $editType.'_addnew.store': $editType.'_edit.update', $action === "create" ? 0 : $id )}} ">
            @csrf
            {{-- <div class="mx-auto max-w-4xl flex-1"> --}}
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
                $control = $value['control'];
                $value_column_name = $action === "edit" ? $values->{$column_name} : "";
                if(is_array($value_column_name)) $value_column_name = 'ARRAY';
                $col_span=$value['col_span'];
                $hiddenRow = $props[$key]['hidden_view_all'] === "true" ? "hidden_view_all":"";
                @endphp
                <div class='col-span-{{$col_span}}'>
                    <div class='grid grid-row-1 gap-3'>
                        <div class='grid grid-cols-12 items-center {{$hiddenRow}}'>
                            <div class='col-start-1 col-span-{{24/$col_span}} text-right'>
                                <label class='block tracking-wide text-gray-800  px-3 text-base' title='{{$column_name}} / {{$control}}'>{{$label}}
                                </label>
                            </div>
                            <div class='col-start-{{24/$col_span + 1}} col-span-10 py-2'>
                                @if (is_null($control))
                                <h2 class="text-red-400">{{"Control of this $column_name has not been set"}}</h2>
                                @endif
                                <strong class="scroll-mt-20 snap-start" id="{{$column_name}}"></strong>
                                @switch ($control)
                                @case($timeControls[0])
                                @case($timeControls[1])
                                @case($timeControls[2])
                                @case($timeControls[3])
                                @case($timeControls[4])
                                @case($timeControls[5])
                                @case($timeControls[6])
                                <x-controls.text colName={{$column_name}} valColName={{$value_column_name}} action={{$action}} :strTimeControl="$timeControls" control={{$control}} />
                                @break
                                @case('text')
                                <x-controls.text colName={{$column_name}} valColName={{$value_column_name}} action={{$action}} :strTimeControl="$timeControls" control={{$control}} />
                                @break

                                @case('id')
                                <x-controls.id colName={{$column_name}} valColName={{$value_column_name}} action={{$action}} :strTimeControl="$timeControls" control={{$control}} />
                                @break

                                @case('textarea')
                                <x-controls.textarea colName={{$column_name}} valColName={{$value_column_name}} action={{$action}} control={{$control}} />
                                @break

                                @case ('dropdown')
                                <x-controls.dropdown id={{$id}} colName={{$column_name}} type={{$type}} tablePath={{$tablePath}} action={{$action}} />
                                @break

                                @case ('radio')
                                <x-controls.radio id={{$id}} colName={{$column_name}} tablePath={{$tablePath}} action={{$action}} />
                                @break

                                @case ('dropdown_multi')
                                <x-controls.dropdownmulti colName={{$column_name}} :idItems="$idItems" action={{$action}} />
                                @break

                                @case('attachment')
                                <x-controls.uploadfiles id={{$id}} colName={{$column_name}} action={{$action}} tablePath={{$tablePath}} />
                                @break

                                @case('switch')
                                <x-controls.toggle id={{$id}} colName={{$column_name}} valColName={{$value_column_name}} />
                                @break

                                @case('checkbox')
                                <x-controls.checkbox id={{$id}} colName={{$column_name}} :idItems="$idItems" action={{$action}} />
                                @break


                                @default
                                {{$control}}
                                @break
                                @endswitch

                                @switch ($action)
                                @case('edit')
                                <x-controls.translationtime tablePath={{$tablePath}} :timeControls="$timeControls" :valColumnNames="$valColumnNames" id={{$id}} control={{$control}} columnName={{$column_name}} />
                                @break
                                @endswitch



                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                {{-- <x-controls.checkbox2 id={{$id}} colName={{$column_name}} :idCbxsItem2="$idCbxsItem2" /> --}}

            </div>
            {{-- </div> --}}
            <div class="flex justify-left border-t dark:bg-gray-800  px-5">
                {{-- {{dd($type)}} --}}
                @switch($action)
                @case('edit')
                <button type="submit" class="mt-5 focus:shadow-outline rounded bg-emerald-500 py-2 px-4 font-bold text-white shadow hover:bg-purple-400 focus:outline-none" type="button">
                    Update
                </button>
                @break
                @case('create')
                <button type="submit" class="mt-5 focus:shadow-outline rounded bg-emerald-500 py-2 px-4 font-bold text-white shadow hover:bg-purple-400 focus:outline-none" type="button">
                    Create
                </button>
                @break
                @endswitch
            </div>
        </form>
    </div>
</div>
@endsection
