@php
    $cu = auth()->user();
    $settings = $cu?->settings;
    $themeBg = $settings['global']['theme-bg'] ?? 'gray-100';
    $themeText = $settings['global']['theme-text'] ?? 'gray-900';

    $bgRGB = '243 244 246';
    switch($themeBg){
        case 'gray-100': $bgRGB = '243 244 246'; break;
        case 'amber-100': $bgRGB = '254 243 199'; break;
        case 'yellow-100': $bgRGB = '254 249 195'; break;
        case 'lime-100': $bgRGB = '236 252 203'; break;
        case 'emerald-100': $bgRGB = '209 250 229'; break;
        case 'teal-100': $bgRGB = '204 251 241'; break;
        case 'cyan-100': $bgRGB = '207 250 254'; break;
        case 'sky-100': $bgRGB = '224 242 254'; break;
        case 'blue-100': $bgRGB = '219 234 254'; break;
        case 'indigo-100': $bgRGB = '224 231 255'; break;
        case 'violet-100': $bgRGB = '237 233 254'; break;
        case 'purple-100': $bgRGB = '243 232 255'; break;
        case 'fuchsia-100': $bgRGB = '250 232 255'; break;
        case 'pink-100': $bgRGB = '252 231 243'; break;
        case 'rose-100': $bgRGB = '255 228 230'; break;

        case 'gray-900': $bgRGB = '17 24 39'; break;
        case 'amber-900': $bgRGB = '120 53 15'; break;
        case 'yellow-900': $bgRGB = '113 63 18'; break;
        case 'lime-900': $bgRGB = '54 83 20'; break;
        case 'emerald-900': $bgRGB = '6 78 59'; break;
        case 'teal-900': $bgRGB = '19 78 74'; break;
        case 'cyan-900': $bgRGB = '22 78 99'; break;
        case 'sky-900': $bgRGB = '12 74 110'; break;
        case 'blue-900': $bgRGB = '30 58 138'; break;
        case 'indigo-900': $bgRGB = '49 46 129'; break;
        case 'violet-900': $bgRGB = '76 29 149'; break;
        case 'purple-900': $bgRGB = '88 28 135'; break;
        case 'fuchsia-900': $bgRGB = '112 26 117'; break;
        case 'pink-900': $bgRGB = '131 24 67'; break;
        case 'rose-900': $bgRGB = '136 19 55'; break;
    }

    $textRGB = '17 24 39';
    switch($themeBg){
        case 'gray-900': $textRGB = '243 244 246'; break;
        case 'amber-900': $textRGB = '254 243 199'; break;
        case 'yellow-900': $textRGB = '254 249 195'; break;
        case 'lime-900': $textRGB = '236 252 203'; break;
        case 'emerald-900': $textRGB = '209 250 229'; break;
        case 'teal-900': $textRGB = '204 251 241'; break;
        case 'cyan-900': $textRGB = '207 250 254'; break;
        case 'sky-900': $textRGB = '224 242 254'; break;
        case 'blue-900': $textRGB = '219 234 254'; break;
        case 'indigo-900': $textRGB = '224 231 255'; break;
        case 'violet-900': $textRGB = '237 233 254'; break;
        case 'purple-900': $textRGB = '243 232 255'; break;
        case 'fuchsia-900': $textRGB = '250 232 255'; break;
        case 'pink-900': $textRGB = '252 231 243'; break;
        case 'rose-900': $textRGB = '255 228 230'; break;

        case 'gray-100': $textRGB = '17 24 39'; break;
        case 'amber-100': $textRGB = '120 53 15'; break;
        case 'yellow-100': $textRGB = '113 63 18'; break;
        case 'lime-100': $textRGB = '54 83 20'; break;
        case 'emerald-100': $textRGB = '6 78 59'; break;
        case 'teal-100': $textRGB = '19 78 74'; break;
        case 'cyan-100': $textRGB = '22 78 99'; break;
        case 'sky-100': $textRGB = '12 74 110'; break;
        case 'blue-100': $textRGB = '30 58 138'; break;
        case 'indigo-100': $textRGB = '49 46 129'; break;
        case 'violet-100': $textRGB = '76 29 149'; break;
        case 'purple-100': $textRGB = '88 28 135'; break;
        case 'fuchsia-100': $textRGB = '112 26 117'; break;
        case 'pink-100': $textRGB = '131 24 67'; break;
        case 'rose-100': $textRGB = '136 19 55'; break;
    }
@endphp

<style>
.bg-body {
    --tw-bg-opacity: 1;
    background-color: rgb({{$bgRGB}} / var(--tw-bg-opacity));
}
.text-body {
    --tw-text-opacity: 1;
    color: rgba({{$textRGB}} / var(--tw-text-opacity));
}
</style>