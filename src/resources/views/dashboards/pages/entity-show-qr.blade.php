@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', "Show QR Code" )

@section('content')
<div class="flex justify-center">
    <div class="w-[1000px] min-h-[1300px] items-center bor9der bg-white box-border flex justify-center">
        <div>
            <div class="w-auto h-auto p-2 bg-white border border-gray-200 dark:bg-gray-800 dark:border-gray-700 rounded-lg transform transition duration-500 hover:scale-110">
                <div class="w-96 h-96 p-3">
                    <img src="https://wp.tlcmodular.com/wp-content/uploads/2020/12/GHT.jpg" alt="image" 
                    class="w-full h-full object-cover">
                </div>
                    <span class="text-base font-semibold flex justify-center truncate">{{$dataSource['name']}}</span>
            </div>
            <div class="w-auto flex justify-center mt-6">
                @foreach($props as $key => $value)
                    @switch($value['control'])
                    @case('hyperlink')
                        <x-renderer.hyper-link2 label="{{$value['column_name']}}" href="{{$dataSource[$value['column_name']]}}" />
                        @break
                    @default
                    @endswitch
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
