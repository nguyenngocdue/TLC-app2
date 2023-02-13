@if ($errors->any())
<div class="w-full flex p-4 mb-4 text-sm text-red-700 bg-red-100  dark:text-red-800 rounded-lg shadow-md dark:bg-gray-800 " role="alert">
    <i class="p-4 mb-4 text-lg fa-duotone fa-circle-xmark"></i>
    <span class="sr-only">Danger</span>
    <div>
        @php
        $outErrors = json_decode($errors->default);
        $colNameErrors = (array)$outErrors;
        @endphp
        <span class="font-medium">Error: The operation failed.</span>
        <ul class="mt-1.5 ml-4 text-blue-700 list-disc list-inside">
            @foreach($colNameErrors as $colName => $errorList)
            @php $message = join(", ", $errorList); @endphp
            <li>
                <a href="#scroll-{{$colName}}" title="{{$colName}}">{{$message}}</a>
            </li>
            @endforeach
        </ul>
    </div>
</div>
@endif
