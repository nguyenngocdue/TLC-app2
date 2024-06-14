{{-- @dd($dataSource) --}}
@php
$sheetDesc = $dataSource['sheet_name']?? 'No Available Sheet Name ';
// $name = App\Utils\Support\Report::slugName($sheetDesc)
$name = $sheetDesc
@endphp
<div id="{{$name}}" class=" md:scroll-mt-20   sm:py-0 rounded-lg  bg-white  dark:border-gray-600">
    <div class="border-b p-3 font-medium">
        <x-renderer.heading level=5>{{strtoupper($sheetDesc)}} - INSPECTION CHECK SHEET</x-renderer.heading>
    </div>
    <div class="flex justify-between p-3">
        <div class="flex">
            <div class="flex flex-col pr-2  font-medium">
                <span>Organization:</span>
                <span>Project:</span>
            </div>
            <div class="flex flex-col">
                <span>{{config("company.name")}}</span>
                <span>{{isset($dataSource['project_name']) ? $dataSource['project_name'] : "" }}</span>
            </div>
        </div>
        <img class="w-40" src="{{asset('logo/moduqa.svg')}}" />
    </div>
</div>
