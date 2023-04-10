<!DOCTYPE html>
<html :class="{ 'theme-dark': dark } " class="scroll-smooth" x-data="data()" lang="en">
@include("layouts/head")
<body>
    @yield('content')
</body>
</html>
