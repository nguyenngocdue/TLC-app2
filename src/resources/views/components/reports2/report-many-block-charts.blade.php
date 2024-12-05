<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach ($chartOptions as $k => $chartOption)
        @php
            $key = uniqid(bin2hex(random_bytes(5)), true);
        @endphp
        <x-reports2.report-chart  
            key="{{$key}}" 
            reportId="{{$reportId}}" 
            :jsonOptions="$chartOption" 
            divClass="{{$divClass}}" 
        /> 
    @endforeach
</div>
