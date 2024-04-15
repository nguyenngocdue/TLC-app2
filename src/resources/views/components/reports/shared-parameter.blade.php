<div class="no-print justify-end pb-5"></div>
<div class="w-full no-print rounded-lg border bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 mb-5 p-3">
    <label for="" class="flex flex-1 text-gray-700 text-lg font-bold dark:text-white">Advanced Filter</label>
    <x-reports.parameter3-report 
                :itemsSelected="$params" 
                modeOption="{{$currentMode}}"
                :columns="$paramColumns" 
                routeName="{{$routeName}}" 
                typeReport="{{$typeReport}}" 
                entity="{{$entity}}" 
                optionPrint="{{$optionPrint}}"
                childrenMode="{{$childrenMode}}"
                type="{{$type}}"
                />
</div>