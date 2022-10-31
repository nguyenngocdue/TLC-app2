<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/tlc2.css') }}">
    <script href="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="flex ">
        <div id="  alert-additional-content-4" class="m-auto mt-[15%] p-4 mb-4 border border-yellow-300 rounded-lg bg-yellow-50 dark:bg-yellow-200" role="alert">
            <div class="flex items-center">
                <svg aria-hidden="true" class="w-5 h-5 mr-2 text-yellow-700 dark:text-yellow-800" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                <span class="sr-only">Info</span>
                <h3 class="text-lg font-medium text-yellow-700 dark:text-yellow-800">{{$title}}</h3>
            </div>
            <div class="mt-2 mb-4 text-sm text-yellow-700 dark:text-yellow-800">
                {!!$error!!}
            </div>
            <div class="flex">
            </div>
        </div>
    </div>
</body>
</html>
