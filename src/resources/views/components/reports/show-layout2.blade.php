<div class="w-full no-print bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600">
    <x-print.setting-layout-report 
        class="{{$classListOptionPrint}}" 
        value="{{$optionPrint}}" 
        entity="{{$entity}}" 
        typeReport="{{$typeReport}}" 
        routeName="{{$routeName}}"
        modeOption="{{$currentMode}}"
    />
</div>