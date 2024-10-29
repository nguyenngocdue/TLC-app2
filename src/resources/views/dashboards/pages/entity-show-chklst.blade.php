@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', "Print" )

@section('content')

<div class="block py-4 bg-body no-print" role="divider"></div>

<div class="flex1 justify-center bg-body bg-only-print print-responsive">
    <div class="md:px1-4 flex-grow flex-shrink-0 w-full overflow-x-auto">  
        <div class="w-90vw items-center bg-white box-border p-4vw mx-auto">
            <x-print.cover-page2 
                avatar="{{$coverAvatar}}" 
                title="{{$coverTitle}}" 
                :dataSource="$coverDataSource"/>
        </div>
    </div>
    <x-renderer.page-break /> 

    <div class="p-4vw w-90vw mx-auto bg-white">
      TOC
    </div>
    <x-renderer.page-break /> 

    {{-- Tried to load by sheetIds but will take 4s instead of 3s --}}
    @foreach($entity->getSheets as $sheet)
        <x-print.insp-chklst.insp-chklst-page :sheet="$sheet"/>
        <x-renderer.page-break /> 
    @endforeach
</div>


@endsection