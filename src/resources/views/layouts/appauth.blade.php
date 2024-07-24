<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/tlc2-20240722a.css') }}" rel="stylesheet">
</head>

<body>
    @yield('content')
</body>
<script>
    const backgroundImageUrls = @json(app()->backgroundImage());
    const textBanners = @json(app()->textBanner());
    const intervalTime = 3 * 1000; // 30 seconds
    const intervalTimeChangeText = 10 * 1000; // 10 seconds
    let currentIndex = 0;
    let currentIndexText = 0;
    const backgroundElement = document.getElementById('background-image');
    const textElement = document.getElementById('text-banner');
    function changeBackground() {
        const nextBackgroundImageUrl = backgroundImageUrls[currentIndex];
        backgroundElement.style.backgroundImage = `url(${nextBackgroundImageUrl})`;
        currentIndex = (currentIndex + 1) % backgroundImageUrls.length;
    }
    function changeTextBanner() {
        const nextTextBanner = textBanners[currentIndexText];
        textElement.textContent = nextTextBanner;
        currentIndexText = (currentIndexText + 1) % textBanners.length;
    }
    changeBackground();
    changeTextBanner();
    setInterval(changeBackground, intervalTime);
    setInterval(changeTextBanner, intervalTimeChangeText);
</script>
</html>
