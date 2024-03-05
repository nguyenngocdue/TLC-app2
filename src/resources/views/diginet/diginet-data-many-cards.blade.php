@extends('layouts.app')

@section('topTitle', 'Update Diginet Data')
@section('title', '')

@section('content')


<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-6">
        @foreach ($dataRender as $key => $items)
            @php
                $routeBtn1 = Route::has($r = $items['card_btn1_route'])? route($r) : "";
                $routeBtn2 = Route::has($r = $items['card_btn2_route'])? route($r) : "";
                $routeBtn3 = Route::has($r = $items['card_btn3_route'])? route($r) : "";
            @endphp

            @if(!str_contains($key,'-lines'))
                <div class="transform hover:scale-105 transition duration-500 ease-in-out">
                    <div class="bg-white rounded-lg overflow-hidden shadow-lg hover:shadow-2xl flex flex-col items-center">
                        <div class="px-6 py-4">
                            <div class="font-bold text-xl text-center">{{$items['card_name']}}</div>
                        </div>
                        <div class="flex px-6 pt-4 pb-2">
                            @if($routeBtn1)
                                <div class="text-center">
                                    <span class="inline-block bg-blue-600 rounded-full px-3 py-1 text-sm font-semibold text-white mr-2 mb-2 cursor-pointer" 
                                                    onclick="openPopup(this)" 
                                                    data-url="{{route($items['card_btn1_route'])}}"
                                                    data-name="{{$items['card_name'] }}"
                                                    >
                                    <i class="fa-solid fa-pen-nib"></i> Update</span>
                                </div>
                            @endif
                            @if($routeBtn2)
                                <div class="text-center">
                                    <span class="inline-block bg-blue-600 rounded-full px-3 py-1 text-sm font-semibold text-white mr-2 mb-2 cursor-pointer" 
                                                    onclick="openPopup(this)" 
                                                    data-url="{{$routeBtn2}}"
                                                    data-name="{{$items['card_name']}}"
                                                    >
                                    <i class="fa-solid fa-eye"></i> Review</span>
                                </div>
                            @endif
                             @if($routeBtn3)
                                <div class="text-center">
                                    <span class="inline-block bg-blue-600 rounded-full px-3 py-1 text-sm font-semibold text-white mr-2 mb-2 cursor-pointer" 
                                                    onclick="openPopupExportCSV(`{{$routeBtn3}}`)"
                                                    data-url="{{$routeBtn2}}"
                                                    data-name="{{$items['card_name']}}"
                                                    >
                                    <i class="fa-duotone fa-file-csv"></i> ExportCSV</span>
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
<div id="popup-container" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden justify-center items-center" style="z-index: 1000;">
    <div class="bg-white p-5 rounded-lg shadow-xl w-[95%] m-auto items-center mt-[5%] border-2 border-gray-200 relative">
        <div class="header shadow-lg p-3 flex justify-between items-center">
            <div class="text-2xl font-semibold text-purple-600">Update Database</div>
            <button onclick="closePopup()" class="text-gray-600 hover:text-gray-900 transition ease-in-out duration-150">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <div class="body p-4">
            <!-- Body content goes here -->
        </div>

        <div class="footer text-left pl-4">
            <x-renderer.button type="secondary" htmlType="submit" click="closePopup()"><i class="fa-solid fa-xmark"></i> Close</x-renderer.button>
        </div>
    </div>
</div>


<!-- Popup Container -->
<div id="popup-container-export-csv" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden justify-center items-center" style="z-index: 1000;">
    <div class="bg-white p-5 rounded-lg shadow-xl w-[95%] m-auto items-center mt-[5%] border-2 border-gray-200 relative">
        <div class="header shadow-lg p-3 flex justify-between items-center">
            <div class="text-2xl font-semibold text-purple-600">Update Database</div>
            <button onclick="closePopup()" class="text-gray-600 hover:text-gray-900 transition ease-in-out duration-150">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <div class="body p-4">
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
    document.getElementById('popup-container').classList.remove('hidden');
    var url = element.getAttribute('data-url'); // get URL from data-url attribute
    var carName = element.getAttribute('data-name');

    var popupBody = $('#popup-container .body');
    popupBody.html(''); // delete old content
    popupBody.append('<div class="font-semibold text-xl pb-4"><strong></strong> ' + carName + '</div>');

    $.ajax({
        url: url,
        type: 'GET',
        success: function(response) {
            var content = $('<div>').html(response);
            popupBody.append(content);
        },
        error: function(xhr, status, error) {
            console.error("An error occurred: " + status + " " + error);
        }
    });
}

function closePopup() {
    document.getElementById('popup-container').classList.add('hidden');
}

function handleEscapeKeyForPopup(event) {
    if (event.key === "Escape" || event.keyCode === 27) {
        closePopup();
    }
}

function openPopupExportCSV(routeBtn3){
    document.addEventListener('keydown', handleEscapeKeyForPopup);
    document.getElementById('popup-container-export-csv').classList.remove('hidden');
    var url = element.getAttribute('data-url'); // get URL from data-url attribute
    var carName = element.getAttribute('data-name');

    var popupBody = $('#popup-container-export-csv .body');
    popupBody.html(''); // delete old content
    popupBody.append('<div class="font-semibold text-xl pb-4"><strong></strong> ' + carName + '</div>');

    $.ajax({
        url: url,
        type: 'GET',
        success: function(response) {
            var content = $('<div>').html(response);
            popupBody.append(content);
        },
        error: function(xhr, status, error) {
            console.error("An error occurred: " + status + " " + error);
        }
    });
}
</script>
@endsection
