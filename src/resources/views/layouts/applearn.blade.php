<!DOCTYPE html>
<script src="{{ asset('AdminLTE/plugins/jquery/jquery.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('css/lightgallery.css') }}">
<script src="{{ asset('js/lightgallery.js') }}"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/justifiedGallery@3.8.1/dist/css/justifiedGallery.min.css">
<script src="https://cdn.jsdelivr.net/npm/justifiedGallery@3.8.1/dist/js/jquery.justifiedGallery.min.js"></script>

<html :class="{ 'theme-dark': dark } " class="scroll-smooth" x-data="data()" lang="en">
@include("layouts/head")
<body>
    @yield('content')
</body>
</html>
