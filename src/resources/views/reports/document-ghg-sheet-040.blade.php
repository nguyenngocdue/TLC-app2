{{-- @props(["a"]); --}}
@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $titleReport)
@section('tooltip', Str::ucfirst($typeReport)." ".$currentMode)
@section('content')
{{-- PARAMETERS --}}
@php
$dataWidgets = $tableDataSource['dataWidgets'];
@endphp
{{-- @dump($tableDataSource) --}}

<div class="px-4">
    @include('components.reports.shared-parameter')
    @include('components.reports.show-layout2')
</div>
@php
        $layout = '';
        switch ($optionPrint) {
            case 'landscape':
            $layout = 'w-[1400px] min-h-[940px]';
            break;
            case 'portrait':
                $layout = 'w-[1000px] min-h-[1450px]';
                break;
            default:
                break;
        }
@endphp
<div class="flex justify-center bg-only-print">
    <div class="md:px-4">
        <div style='' class="w-[1400px] min-h-[990px] items-center bg-white box-border p-8">
            <div class="pt-5">
                <x-print.header6 />
            </div>
            @include('reports.include-document-ghg-sheet-040', ['tableDataSource' => $tableDataSource , 'params' =>$params])

        </div>
    </div>
</div>
@endsection