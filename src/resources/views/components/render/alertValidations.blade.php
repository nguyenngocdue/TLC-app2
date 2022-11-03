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
        // dd($colNameErrors);
        @endphp
        <span class="font-medium">Error: The operation failed.</span>
        <ul class="mt-1.5 ml-4 text-red-700 list-disc list-inside">
            @foreach($colNameErrors as $key => $value)
            <li>
                <a href="#{{$key}}" title="{{$key}}">{{$value[0]}}</a>
            </li>
            @endforeach
        </ul>
    </div>
</div>
@endif

@if(Session::has('error'))
<div class="alert alert-danger">
    {{ Session::get('error') }}
</div>
@endif

@if(Session::has('success'))
<div class="alert alert-success">
    {{ Session::get('success') }}
</div>
@endif
