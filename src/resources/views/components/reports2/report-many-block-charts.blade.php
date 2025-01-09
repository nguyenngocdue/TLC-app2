@if($chartOptions)
    @foreach ($chartOptions as $chartOption)
        @php
            $opts = $chartOption['chart_option'];
            $des = $chartOption['descriptions'];
            $class = $des['cssClass'];
        @endphp
        <x-renderer.heading class="{{$class}}" level=4>{{$des['text']}}</x-renderer.heading>
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
@endif
