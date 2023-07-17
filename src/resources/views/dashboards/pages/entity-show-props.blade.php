@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', "Show" )

@section('content')
<script>
    k = @json($listenerDataSource);

    listenersOfDropdown2 = @json($listeners2);
    filtersOfDropdown2 = @json($filters2);

    listenersOfDropdown4s = @json($listeners4);
    filtersOfDropdown4s = @json($filters4);
</script>
<x-print.setting-layout5 class="{{$classListOptionPrint}}" value="{{$valueOptionPrint}}" type="{{$typePlural}}"/>
@php
        switch ($valueOptionPrint) {
            case 'landscape':
            $layout = 'w-[1400px] min-h-[1000px]';
            break;
            case 'portrait':
            default:
                $layout = 'w-[1000px] min-h-[1355px]';
                break;
        }
@endphp
<div class="flex justify-center">
    <div class="{{$layout}} items-center bor1der bg-white box-border p-8">
        <x-print.letter-head5 showId={{$showId}} type={{$type}} :dataSource="$dataSource" />
        @foreach($propsTree as $propTree)
        <x-print.description-group5 type={{$type}} modelPath={{$modelPath}} :propTree="$propTree" :dataSource="$dataSource" />
        @endforeach
        <x-print.printed-time-zone />
        <div class="fixed top-52 right-0 no-print">
            <x-controls.action-buttons isFloatingOnRightSide="true" :buttonSave="$buttonSave" action="edit" :actionButtons="$actionButtons" :propsIntermediate="$propsIntermediate"/>
        </div>
    </div>
</div>
<div class="no-print">
    <form action="{{$routeUpdate}}" id="form-upload" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input name="tableNames[table00]" value="(the_form)" type='hidden' /> {{-- This line is required for updating  --}}
        <input type="hidden" name="status" id="status" value="{{$status}}">
        @foreach($propsIntermediate as $key => $props)
                @php $propsOfIntermediatePage = App\Utils\Support\WorkflowFields::parseFields($props, $values, $defaultValues, $status, $type); @endphp
                <x-renderer.editable.modal-intermediate key={{$key}} action="edit" type={{$typePlural}} status={{$status}} id={{$showId}} modelPath={{$modelPath}} :actionButtons="$actionButtons" :props="$props" :item="$item" :dataSource="$propsOfIntermediatePage"  />
        @endforeach
    </form>
</div>

@endsection
