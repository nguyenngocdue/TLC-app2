<div id="{{$name}}" class=" md:scroll-mt-20   sm:py-0 rounded-lg  bg-white  dark:border-gray-600">
    <div class="border-b p-3 font-medium flex justify-center">
        <x-renderer.heading level=5>{{strtoupper($name)}} - INSPECTION CHECK SHEET</x-renderer.heading>
    </div>
    <div class="flex justify-between p-3">
        <div class="flex">
            <div class="flex flex-col pr-2  font-medium">
                <span>Organization:</span>
                <span>Project Name:</span>
                <span>Sub Project Name:</span>
                <span>Prod Order Name:</span>
            </div>
            <div class="flex flex-col font-normal">
                <span>TLC Modular</span>
                <span>{{$projectName}}</span>
                <span>{{$subProjectName}}</span>
                <span>{{$prodOrderName}}</span>
            </div>
        </div>
        <div class="w-48">
            <img src="{{asset('logo/tlc.png')}}" />
        </div>
    </div>
    <div class="border-t p-3 font-medium flex justify-start">
        <div class="flex">
            <div class="flex flex-col pr-2  font-medium">
                <span>Consent Number:</span>
            </div>
            <div class="flex flex-col font-normal">
                <span>{{$consentNumber}}</span>
            </div>
        </div>
    </div>
</div>