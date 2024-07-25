{{-- @dd($dataSource) --}}

<div class="">
    <img class="w-40" src="{{asset('logo/moduqa.svg')}}">
    <div class=" border-b border-t bg-gray-300 rounded-lg">
        <x-renderer.heading level=3 class='text-center'>TLC - INSPECTION CHECK SHEET</x-renderer.heading>
    </div>
    <div class="px-3 flex ">
        <div class=" pr-3">
            <x-renderer.heading level=6>Project:</x-renderer.heading>
            <x-renderer.heading level=6>Sub Project:</x-renderer.heading>
            <x-renderer.heading level=6>Prod Order:</x-renderer.heading>
            <x-renderer.heading level=6>Name:</x-renderer.heading>
            <x-renderer.heading level=6>Inspect Check List:</x-renderer.heading>
        </div>
        <div class="">
            <x-renderer.heading level=6>{{$dataSource['project_name']}}</x-renderer.heading>
            <x-renderer.heading level=6>{{$dataSource['sub_project_name']}}</x-renderer.heading>
            <x-renderer.heading level=6>{{$dataSource['po_name']}}</x-renderer.heading>
            <x-renderer.heading level=6>{{$dataSource['compliance_name']}}</x-renderer.heading>
            <x-renderer.heading level=6>{{$dataSource['chklsts_name']}}</x-renderer.heading>
        </div>
    </div>
</div>
