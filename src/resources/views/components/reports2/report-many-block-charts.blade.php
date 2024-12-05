    @foreach ($chartOptions as $chartOption)
        @php
            $opts = $chartOption['option_chart'];
            $des = $chartOption['descriptions'];
        @endphp
        <x-renderer.heading class="py-6 text-left font-bold " level=4>{{$des['text']}}</x-renderer.heading>
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
