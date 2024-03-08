@extends('layouts.app')

@section('topTitle', 'Update Diginet Data')
@section('title', '')

@section('content')

{{-- @if(Session::has('toastr_message'))
@dump(132)
    <script>
        toastr.success('{{ Session::get('toastr_message') }}');
    </script>
@endif
 --}}
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-2 gap-6 ">
        @foreach ($dataRender as $key => $items)
            @php
                $routeBtn1 = Route::has($r = $items['card_btn1_route'])? route($r) : "";
                $classAdd = $key === "all-tables" ? "col-span-2 w-[50%] justify-self-center" : "";
            @endphp

            @if(!str_contains($key,'-lines'))
                <div class="transform hover:scale-105 transition duration-500 ease-in-out {{$classAdd}}">
                    <div class="bg-white rounded-lg overflow-hidden shadow-lg hover:shadow-2xl flex flex-col items-center">
                        <div class="px-4 py-2">
                            <div class="font-bold text-xl text-center text-gray-700">{{$items['card_name']}}</div>
                        </div>
                        <div class="flex px-4 pt-2 pb-2">
                            {{-- UPDATE --}}
                            @if($routeBtn1)
                                <div class="text-center">
                                    <span class="inline-block bg-blue-600 rounded-full px-3 py-1 text-sm font-semibold text-white mr-2 mb-2 cursor-pointer" 
                                                    onclick="openPopup(this)" 
                                                    data-url="{{route($items['card_btn1_route'])}}"
                                                    data-name="{{$items['card_name'] }}"
                                                    top-title="Update Datasource"
                                                    >
                                    <i class="fa-solid fa-pen-nib"></i> Update</span>
                                </div>
                            @endif
                            {{-- REVIEW --}}
                            @php
                                $arrayRouteReview = isset($items['card_btn2_route'])? $items['card_btn2_route'] : null;
                            @endphp
                            @if($arrayRouteReview)
                                @php
                                    $routeViewFromNames = App\Utils\Support\Diginet::getRouteFromNames($arrayRouteReview);
                                @endphp
                                <div class="text-center">
                                    <span class="inline-block bg-blue-600 rounded-full px-3 py-1 text-sm font-semibold text-white mr-2 mb-2 cursor-pointer" 
                                                    onclick='openPopupShowEntities({!! json_encode($routeViewFromNames) !!}, this)'
                                                    data-name="{{$items['card_name']}}"
                                                    top-title="Review Datasource"
                                                    type-click="review"
                                                    >
                                    <i class="fa-solid fa-eye"></i> Review</span>
                                </div>
                            @endif
                            {{-- EXPORT EXCEL --}}
                            @php
                                $arrayRoutesEx = isset($items['card_btn3_route'])? $items['card_btn3_route'] : null;
                            @endphp
                            @if($arrayRoutesEx)
                                @php
                                    $routeExFromNames = App\Utils\Support\Diginet::getRouteFromNames($arrayRoutesEx);
                                @endphp
                                <div class="text-center">
                                    <span class="inline-block bg-blue-600 rounded-full px-3 py-1 text-sm font-semibold text-white mr-2 mb-2 cursor-pointer" 
                                                    onclick='openPopupShowEntities({!! json_encode($routeExFromNames) !!}, this)'
                                                    data-name="{{$items['card_name']}}"
                                                    top-title="Export Datasource"
                                                    type-click="export_excel"
                                                    >
                                    <i class="fa-duotone fa-file-csv"></i> ExportCSV</span>
                                </div>
                            @endif
                            {{-- DELETE --}}
                             @php
                                $arrayRoutesDel = isset($items['card_btn4_route'])? $items['card_btn4_route'] : null;
                            @endphp
                            @if($arrayRoutesDel)
                                @php
                                    $arrayRoutesDel = App\Utils\Support\Diginet::getRouteFromNames($arrayRoutesDel);
                                @endphp
                                <div class="text-center">
                                    <span class="inline-block bg-red-500 rounded-full px-3 py-1 text-sm font-semibold text-white mr-2 mb-2 cursor-pointer" 
                                                    onclick='deleteDatabase({!! json_encode($arrayRoutesDel) !!}, this)'
                                                    type-click="delete"
                                                    data-name="{{$items['card_name']}}"
                                                    top-title="Delete Datasource">
                                    <i class="fa-solid fa-trash"></i> Delete</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>

<!-- Popup Container -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<div id="popup-container" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden justify-center items-center" style="z-index: 1000;">
    <div class="bg-white p-5 rounded-lg shadow-xl w-[95%] m-auto items-center mt-[5%] border-2 border-gray-200 relative">
        <div class="header shadow-lg p-3 flex justify-between items-center">
            <div id="top-title" class="text-2xl font-semibold text-purple-600">Update Database</div>
            <button onclick="closePopup()" class="text-gray-600 hover:text-gray-900 transition ease-in-out duration-150 hover:bg-slate-300">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="body p-4 ">
            <!-- Body content goes here -->
        </div>

        <div class="footer text-left pl-4">
            <x-renderer.button type="secondary" htmlType="submit" click="closePopup()"><i class="fa-solid fa-xmark"></i> Close</x-renderer.button>
        </div>
    </div>
</div>


<script>
    function openPopup(element) {
        document.addEventListener('keydown', handleEscapeKeyForPopup);
        document.getElementById('top-title').innerHTML = element.getAttribute('top-title');
        document.getElementById('popup-container').classList.remove('hidden');
        
        var _url = element.getAttribute('data-url'); // get URL from data-url attribute
        var carName = element.getAttribute('data-name');
        var popupBody = $('#popup-container .body');

        popupBody.html(''); // delete old content
        popupBody.append('<div class="font-semibold text-2xl pb-4 text-gray-700"><strong></strong> ' + carName + '</div>');

        $.ajax({
            url: _url,
            type: 'GET',
            success: function(response) {
                var content = $('<div>').html(response);
                popupBody.append(content);
            },
            error: function(xhr, status, error) {
                console.error('Error sending GET request', xhr, status, error);
            }
        });
    }

</script>
@endsection

