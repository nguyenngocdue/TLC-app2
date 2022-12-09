@if ($errors->any())
<div class="w-full flex p-4 mb-4 text-sm text-red-700 bg-red-100  dark:text-red-800 rounded-lg shadow-md dark:bg-gray-800 " role="alert">
    <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
    </svg>
    <span class="sr-only">Danger</span>
    <div>
        @php
        $outErrors = json_decode($errors->default);
        $colNameErrors = (array)$outErrors;
        @endphp
        <span class="font-medium">Error: The operation failed.</span>
        <ul class="mt-1.5 ml-4 text-red-700 list-disc list-inside">
            @foreach($colNameErrors as $colName => $value)
            <li>
                @foreach($props as $keyProps => $prop)
                @if ($prop['column_name']=== $colName)
                {{-- @dump($colNameErrors) --}}
                @php
                $strSearch = str_contains($colName, '_') ? str_replace('_', ' ', $colName) : $colName;
                @endphp
                <a href="#{{$colName}}" title="{{$colName}}">{{str_replace($strSearch, "[".$prop['label']."]", $value[0])}}</a>
                @endif
                @endforeach
            </li>
            @endforeach
        </ul>
    </div>
</div>
@endif
