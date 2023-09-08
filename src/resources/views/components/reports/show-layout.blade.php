@php
    switch ($valueOptionPrint) {
        case 'landscape':
            $layout = 'w-[1400px] min-h-[1000px]';
            break;
        case 'portrait':
        default:
            $layout = 'w-[1000px] min-h-[1360px]';
            break;
    }
@endphp
<div class="w-full no-print bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600">
    <x-print.setting-layout5 class="{{$classListOptionPrint}}" value="{{$valueOptionPrint}}" type="{{$entity}}" />
</div>