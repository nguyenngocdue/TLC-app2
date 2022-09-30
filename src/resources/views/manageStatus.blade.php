<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
    <script href="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.tailwindcss.com"></script>


</head>
<body>


    <div class="grid grid-row-1 gap-4 bg-black">
        <div class="grid grid-cols-12 gap-4 text-center">
            <div class="bg-slate-300 col-span-6">
                <h3>All statuses of this post type</h3>
                @foreach($statusPostType as $key => $prop)
                <div class="flex justify-end  bg-white border border-gray-200 w-full text-gray-900 ">
                    <div class="flex hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-0 focus:bg-gray-200 focus:text-gray-600">
                        <span class="col-span-3 bg-[{{$prop['color']}}]">{{$prop['title']}}</span>
                        <form id="form-upload" method="POST" , action="{{route('status.store', 1) }}">
                            @csrf
                            <button type="submit" name="move_up" value={{$prop['name']}} class="bg-blue-300 hover:bg-white-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11l7-7 7 7M5 19l7-7 7 7"></path>
                                </svg>
                            </button>
                            <button type="submit" name="move_down" value={{$prop['name']}} class="bg-blue-300 hover:bg-white-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 13l-7 7-7-7m14-8l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <button type="submit" name="remove" value={{$prop['name']}} class="bg-blue-300 hover:bg-white-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="bg-slate-300 col-span-6">
                <h3>All available statuses</h3>
                @php
                $nameStatus = isset($nameStatus) ? $nameStatus : "";
                @endphp

                @foreach($newAvailabeStatus as $key => $prop)
                <div class="flex justify-start bg-white border border-gray-200 w-full text-gray-900 ">
                    <div class=" flex hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-0 focus:bg-gray-200 focus:text-gray-600">
                        <form id="form-upload" method="POST" , action="{{route('status.store', 1) }}">
                            @csrf
                            <button type="submit" name="add" value={{$prop['name']}} class="bg-blue-300 hover:bg-white-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                                </svg>
                            </button>
                        </form>
                        <span class="col-span-3 bg-[{{$prop['color']}}]">{{$prop['title']}}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</body>
</html>
