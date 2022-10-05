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
    <form class="rounded-md bg-white p-4" id="form-upload" method="POST" action="{{route('manage_statusLibrary.update', 1) }}">
        @csrf
        @method('PUT')
        <table id="table_manage" class="whitespace-no-wrap w-full table-auto py-10">
            <thead class="text-xs text-gray-700 uppercase bg-gray-400 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th></th>
                    <th scope="col " class="py-3 px-6 text-base">
                        Name
                    </th>
                    <th scope="col" class="py-3 px-6 text-base">
                        Title
                    </th>
                    <th scope="col" class="py-3 px-6 text-base">
                        Color
                    </th>
                    <th scope="col" class="py-3 px-6 text-base">
                        Render
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y bg-white dark:divide-gray-700 dark:bg-gray-800">
                @foreach($libStatus as $key => $props)
                <tr class=" text-gray-700 dark:text-gray-400 bg-slate-200">
                    <td class=" text-3xl flex justify-center">{{$key}}</td>
                    <td class=" text-3xl flex justify-center"></td>
                    <td class=" text-3xl flex justify-center"></td>
                    <td class=" text-3xl flex justify-center"></td>
                    <td class=" text-3xl flex justify-center"></td>
                </tr>
                @foreach($props as $key => $prop)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <td class="text-base py-4 px-6">
                        <input hidden>
                    </td>
                    <td class="text-base py-4 px-6 text-center">
                        <input readonly name="oldName[]" class="focus:outline-none border-none  bg-white text-center font-bold" value={{$key}}>
                    </td>
                    <td class="text-base py-4 px-6">
                        <div class="flex">
                            <input id="{{"title-".$prop['title'].$key}}" title="Click on here to edit" name="oldTitle[]" class="text-center border-2 border-gray-900 w-full focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm" type="text" value="{{$prop['title']}}">

                        </div>
                    </td>
                    <td class="text-base py-4 px-6 ">
                        <div class="flex">
                            <input id="{{$prop['color'].$key}}" title="Click on here to edit" name="oldColor[]" class=" border-2  text-center border-gray-700  w-full focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm    bg-[{{$prop['color']}}]" type="text" value="{{$prop['color']}}">
                        </div>
                    </td>
                    <td class="text-base py-4 px-6 text-white flex justify-center">
                        <span class=" bg-[{{$prop['color']}}]">{{$prop['title']}}</span>
                    </td>
                </tr>
                @endforeach

                @endforeach


                <tr class=" border-b dark:bg-gray-800 dark:border-gray-700">
                    <td class=" ">
                        <button class="focus:shadow-outline rounded bg-emerald-500 block text-white font-bold text-xl text-center px-2" for="createStatus">
                            Create
                        </button>
                    </td>
                    <td class="bg-slate-400 text-base py-2 px-6 ">
                        <input name="name[]" class="w-full text-center border focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm" id="createStatus" type="text" placeholder="Enter name">
                    </td>
                    {{-- <td class=" bg-slate-400 text-base py-2 px-6">
                        <input name="title" class="w-full text-center border focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm" type="text" placeholder="Enter title">
                    </td>
                    <td class="bg-slate-400 text-base py-2 px-6">
                        <input name="color" class="w-full text-center border focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm" type="text" placeholder="Enter color">
                    </td>
                    <td class="bg-slate-400"></td> --}}
                </tr>
            </tbody>
        </table>
        <button type="submit" class="mt-5 focus:shadow-outline rounded bg-emerald-500 py-2 px-4 font-bold text-white shadow hover:bg-purple-400 focus:outline-none" type="button">
            Update
        </button>
    </form>
</div>

@endsection
