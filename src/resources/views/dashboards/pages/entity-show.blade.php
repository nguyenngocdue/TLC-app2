@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', "Show" )

@section('content')
{{-- @dd($dataSource) --}}
<div class="flex justify-between no-print bg-gray-50 dark:bg-gray-800 text-purple-600 dark:text-purple-300 mb-2">
    <div class="flex-1"></div>
    <form action="{{route('updateUserSettings')}}" class="w-28" method="post">
        @method('put')
        @csrf
        <input type="hidden" name="_entity" value="{{$typePlural}}">
        <input type="hidden" name="action" value="updateOptionPrintLayout">
        <select 
        name="option_print_layout" 
        class="{{$classListOptionPrint}}" onchange="this.form.submit()">
        <option value="portrait" @selected($valueOptionPrint == 'portrait')>Portrait</option>
        <option value="landscape" @selected($valueOptionPrint == 'landscape')>Landscape</option>
        </select>
    </form>
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
</div>
<div class="flex justify-center">
    <div class="{{$layout}} items-center bor1der bg-white box-border p-8">
        <x-print.letter-head5 showId={{$showId}} type={{$type}} :dataSource="$dataSource" />
        @foreach($propsTree as $propTree)
        <x-print.description-group5 type={{$type}} modelPath={{$modelPath}} :propTree="$propTree" :dataSource="$dataSource" />
        @endforeach

    </div>
</div>
@endsection
