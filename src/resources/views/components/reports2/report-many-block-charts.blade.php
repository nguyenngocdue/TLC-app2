@if($chartOptions)
    @foreach ($chartOptions as $chartOption)
        @php
            $opts = $chartOption['chart_option'];
            $des = $chartOption['descriptions'];
        @endphp
        <x-renderer.heading class="text-lg text-lg-vw leading-tight text-blue-600 bg-gray-50 dark:bg-gray-700 dark:text-gray-400 p-4 mb-2" level=4>{{$des['text']}}</x-renderer.heading>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($opts as $opt) 
                    @php
                        $key = uniqid(bin2hex(random_bytes(5)), true);
                    @endphp
                    <x-reports2.report-chart  
                        key="{{$key}}" 
                        reportId="{{$reportId}}" 
                        :jsonOptions="$opt" 
                        divClass="{{$divClass}}" 
                    /> 
            @endforeach 
        </div>
    @endforeach
@else
    <div class="max-w-md mx-auto bg-white shadow-lg rounded-lg p-6 text-center">
        <!-- Icon -->
        <div class="text-red-500 mb-4">
            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 9.172a4 4 0 015.656 0l5.657 5.657a4 4 0 01-5.656 5.656L9.172 14.83a4 4 0 010-5.656zm-1.415 1.415a6 6 0 118.486 8.486"></path>
            </svg>
        </div>
        <!-- Text -->
        <h1 class="text-2xl font-semibold text-gray-800 mb-2">No Data Available</h1>
        <p class="text-gray-600">The data source is empty. Please check again later.</p>
    </div>
@endif
