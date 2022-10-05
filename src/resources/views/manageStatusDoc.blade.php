@extends('layouts.app')
@section('content')
<div>
    <div class="focus:shadow-outline-purple my-4 flex items-center justify-between rounded-lg bg-purple-600 p-3 text-base font-semibold text-purple-100 shadow-md focus:outline-none">
        <div class="flex items-center">
            <svg class="mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                </path>
            </svg>
            <span>{{$type}}</span>
        </div>
    </div>
    <div class="grid grid-row-1 gap-4 bg-black">
        <div class="grid grid-cols-12 gap-4 text-center">
            <div class="bg-slate-300 col-span-6">
                <h3 class="py-3 px-6 text-base">All statuses of this doc type</h3>
                @foreach($newStatusDocType as $key => $prop)
                <div class="flex justify-end  bg-white border {{empty($prop['bg-orphan']) ? "" : $prop['bg-orphan']}} border-gray-200 w-full text-gray-900 ">
                    <div class="flex hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-0 focus:bg-gray-200 focus:text-gray-600">
                        <span class="p-2 col-span-3 bg-[{{$prop['color']}}]">{{$prop['title']}}</span>
                        <form id="form-upload" method="POST" , action="{{route('status.store', 1) }}">
                            @csrf
                            <button type="submit" name="move_up" value={{$prop['name']}} class="bg-blue-300 hover:bg-white-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11l7-7 7 7M5 19l7-7 7 7"></path>
                                </svg>
                            </button>
                            <button type="submit" name="move_down" value={{$prop['name']}} class=" bg-blue-300 hover:bg-white-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
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
                <h3 class="py-3 px-6 text-base">All available statuses</h3>
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
                        <span class="p-2 col-span-3 bg-[{{$prop['color']}}]">{{$prop['title']}}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>


</div>

@endsection
