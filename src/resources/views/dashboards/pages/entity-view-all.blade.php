@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', "View All" )

@section('content')
<div class="px-4">
    <div class="flex flex-wrap pb-5">
        <x-form.search route="{{ route($type . '.index') }}" title="{{$searchTitle}}" />
        <div class="w-full lg:w-1/3 p-2 lg:p-0 items-center">
            <div class="lg:flex lg:justify-center lg:gap-2">
                <x-renderer.button title="Export this list to CSV">
                    <a href="{{route($type.'_ep.exportCSV')}}" target="_blank">
                        <i class="fa-duotone fa-file-csv"></i>
                    </a>
                </x-renderer.button>
                <x-renderer.button title="Print this list QR Code">
                    <a href="{{route($type.'_qr.showQRCode')}}">
                        <i class="fa-duotone fa-qrcode"></i>
                    </a>
                </x-renderer.button>
                <x-modal-settings type="{{$type}}"/>
            </div>
                
                
        </div>
        <x-form.per-page type="{{$type}}" route="{{ route('updateUserSettings') }}" perPage="{{$perPage}}" />
    </div>
    <x-renderer.advanced-filter :type="$type" :valueAdvanceFilters="$valueAdvanceFilters"/>
    <x-renderer.table showNo="true" :columns="$columns" :dataSource="$dataSource" />
    <div class="flex">
        <x-renderer.button size='xs' value='$name' type='default' onClick='actionDuplicated()' >
            <input id="{{$type}}_checkbox_all" type="checkbox">
                Check all
        </x-renderer.button>
        <x-renderer.button size='xs' value='$name' type='secondary' onClick='actionDuplicated()' ><i class='fa fa-copy'></i> Duplicate All</x-renderer.button>
        <x-renderer.button size='xs' value='$name' type='danger' onClick='actionDeleted()' ><i class='fa fa-trash'></i> Delete All</x-renderer.button>
    </div>
</div>
<br />
<script src="{{ asset('js/renderprop.js') }}"></script>
@endsection
