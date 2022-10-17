<!DOCTYPE html>
<html lang="en">
<head>
    <!DOCTYPE html>
    <html :class="{ 'theme-dark': dark }" x-data="data()" lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/fontawesome-free/css/all.min.css') }}">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
</head>
<body>
    <div class="focus:shadow-outline-purple my-4 flex items-center justify-between rounded-lg bg-red-600 p-3 text-base font-semibold text-purple-100 shadow-md focus:outline-none">
        <div class="flex items-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>ERROR: {{$error}}</span>
        </div>
    </div>
</body>
</html>
