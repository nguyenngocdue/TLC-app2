{{-- @dd($dataSource) --}}
@php
$sheetDesc = $dataSource['sheet_name'];
$name = App\Utils\Support\Report::slugName($sheetDesc)
@endphp
<div id="{{$name}}" class=" md:scroll-mt-20   sm:py-0 rounded-lg  bg-white border-t border-r border-l border-gray-300 dark:border-gray-600">
    <div class="border-b p-3 font-medium">
        <span class="">{{strtoupper($sheetDesc)}} - INSPECTION CHECK SHEET</span>
    </div>
    <div class="flex justify-between border-b p-3">
        <div class="flex">
            <div class="flex flex-col pr-2  font-medium">
                <span>Organization:</span>
                <span>Project Name:</span>
            </div>
            <div class="flex flex-col">
                <span>TLC Modular</span>
                <span>{{$dataSource['project_name']}}</span>
            </div>
        </div>
        <img class="w-40" src="https://assets.website-files.com/61e52058abc83b0e8416a425/61f0ce6fe8161c72f61be858_logo-blue.svg" />
    </div>
</div>
