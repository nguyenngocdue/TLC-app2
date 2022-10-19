@extends('layouts.app')
@section('content')
    <div class="flex">
        <div id="  alert-additional-content-4"
            class="m-auto mt-[15%] mb-4 rounded-lg border border-yellow-300 bg-yellow-50 p-4 dark:bg-yellow-200"
            role="alert">
            <div class="flex items-center">
                <svg aria-hidden="true" class="mr-2 h-5 w-5 text-yellow-700 dark:text-yellow-800" fill="currentColor"
                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                        clip-rule="evenodd"></path>
                </svg>
                <span class="sr-only">Info</span>
                <h3 class="text-lg font-medium text-yellow-700 dark:text-yellow-800">{{ $title }}</h3>
            </div>
            <div class="mt-2 mb-4 text-sm text-yellow-700 dark:text-yellow-800">
                {!! $error !!}
            </div>
            <div class="flex">
                <!-- <button type="button" class="mr-2 inline-flex items-center rounded-lg bg-yellow-700 px-3 py-1.5 text-center text-xs font-medium text-white hover:bg-yellow-800 focus:outline-none focus:ring-4 focus:ring-yellow-300 dark:bg-yellow-800 dark:hover:bg-yellow-900">
                    <svg aria-hidden="true" class="-ml-0.5 mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                    </svg>
                    View more
                </button>
                <button type="button" class="rounded-lg border border-yellow-700 bg-transparent px-3 py-1.5 text-center text-xs font-medium text-yellow-700 hover:bg-yellow-800 hover:text-white focus:outline-none focus:ring-4 focus:ring-yellow-300 dark:border-yellow-800 dark:text-yellow-800 dark:hover:text-white" data-dismiss-target="#alert-additional-content-4" aria-label="Close">
                    Dismiss
                </button> -->
            </div>
        </div>
    </div>
@endsection
