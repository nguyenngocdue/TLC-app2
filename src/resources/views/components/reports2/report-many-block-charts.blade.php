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
