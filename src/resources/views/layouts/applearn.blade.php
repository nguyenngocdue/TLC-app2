<!DOCTYPE html>
<script src="{{ asset('AdminLTE/plugins/jquery/jquery.min.js') }}"></script>

<html :class="{ 'theme-dark': dark } " class="scroll-smooth" x-data="data()" lang="en">
@include("layouts/head")
<body>
    @yield('content')
</body>
</html>
