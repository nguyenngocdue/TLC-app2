@extends('layouts.app')
@section('topTitle', $topTitle)
@section('title', '')


@section('content')
@if(!app()->isProduction())
<div class="h-screen">
    <div class="bg-white sha1dow h-screen dark:bg-[#18191a]">
            <div class="profile-header">
                <div class="px-44 shadow">
                    <div class="relative h-[400px] rounded-b flex justify-start">
                        <img
                            src="{{asset('images/avatar.jpg')}}"
                            class="object-cover w-full h-full rounded-b"
                            alt="cover"
                        />
                        <div class="absolute -bottom-32 left-14">
                            <img
                                src="{{asset('images/avatar.jpg')}}"
                                class="object-cover border-4 border-white w-44 h-44 rounded-full"
                                alt="cover"
                            />
                        </div>
                    </div>
                    <div class="ml-72 mt-8">
                            <h3 class="font-semibold text-3xl">Foden Ngo</h3>
                            <h5>Position</h5>
                    </div>
                    <div class="border mt-12 border-opacity-10"></div>
                        <div class="flex justify-between px-8">
                            <div class="flex items-center">
                                <div class="px-4 py-5 text-fBlue border-b-4 border-fBlue cursor-pointer">
                                Posts
                                </div>
                                <div class="px-4 py-5 text-fGrey cursor-pointer">Friends</div>
                                <div class="px-4 py-5 text-fGrey cursor-pointer">Photos</div>
                                <div class="px-4 py-5 text-fGrey cursor-pointer">Videos</div>
                                <div class="px-4 py-5 text-fGrey cursor-pointer">Check-Ins</div>
                                <div class="px-4 flex items-center py-5 text-fGrey cursor-pointer">
                                More
                                <i class="ml-2 fa-duotone fa-caret-down"></i>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button class="w-12 h-9 bg-fButton rounded flex items-center justify-center focus:outline-none bg-gray-300">
                                    <i class="fa-solid fa-phone"></i>
                                </button>
                                <button class="w-12 h-9 bg-fButton rounded flex items-center justify-center focus:outline-none bg-gray-300">
                                    <i class="fa-solid fa-user"></i>
                                </button>
                                <button class="w-12 h-9 bg-fButton rounded flex items-center justify-center focus:outline-none bg-gray-300">
                                    <i class="fa-sharp fa-light fa-circle-ellipsis"></i>
                                </button>
                            </div>
                        </div>
                </div>
            </div>
            <div class="prodile-content">
                <div class="px-44">
                    {{-- <x-social.sidebar >

                    </x-social.sidebar> --}}
                    
                </div>
            </div>
    </div>
</div>

@endif
@endsection