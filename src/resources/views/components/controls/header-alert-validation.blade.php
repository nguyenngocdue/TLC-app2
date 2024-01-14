@if ($errors->any())
{{-- @dump($errors) --}}
<div class="w-full flex p-4 mb-4 text-sm text-red-700 bg-red-100  dark:text-red-800 rounded-lg shadow-md dark:bg-gray-800 " role="alert">
    <div class="p-4 mb-4 text-5xl" >
        <i class="fa-duotone fa-circle-xmark"></i>
        <span class="sr-only">Danger</span>
    </div>
    <div>
        @php $colNameErrors = (array)json_decode($errors->default); @endphp
        <span class="font-medium">Error: This file can't save due to the following issues:</span>
        <ul class="mt-1.5 ml-4 text-red-700 list-disc list-inside">
            @foreach($colNameErrors as $colName => $errorList)
            {{-- @dump($errorList) --}}
            @php $label = $props["_".$colName]['label'] ?? "In table line: ".Str::headline($colName); @endphp
            @php $control = $props["_".$colName]['control'] ?? "Control?"; @endphp
            <li>
                <a class="underline" href="#scroll-{{$colName}}" title="{{$colName}} - {{$control}}">{{$label}}:</a>
                <ul class="mt-1.5 ml-4 text-red-700 list-disc list-inside">
                    @foreach($errorList as $msg)
                    <li class="text-xs">{{$colName}}: {{$msg}}</li>
                    @endforeach
                </ul>
            </li>
            @endforeach
        </ul>
    </div>
</div>
@endif
