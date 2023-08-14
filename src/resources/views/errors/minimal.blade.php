<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <title>@yield('title')</title>
        <style>
            body {
                font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            }
        </style>
</head>
    <body class="antialiased">
        <div id="app" class="relative flex items-top justify-center min-h-screen bg-gray-200 dark:bg-gray-900 sm:items-center sm:pt-0">
                <div class="w-1/2 mx-auto sm:px-6 lg:px-8 bg-white shadow-lg rounded">
                    @php
                        $route = App\Utils\Support\CurrentRoute::getName();
                        $url = false;
                        switch (true) {
                            case str_contains($route,'.show'):
                            case str_contains($route,'.edit'):
                                $url = route(substr_replace($route,'.index',strpos($route, '.'))) ?? '/';
                                break;
                            default:
                                break;
                        }
                    @endphp
                    <antd-results :code="@yield('code')" :message="'@yield('message')'" :url="'{{$url}}'"/>
                </div>
        </div>
        <script src="{{ asset('js/antd-vue.js') }}"></script>
    </body>


</html>
