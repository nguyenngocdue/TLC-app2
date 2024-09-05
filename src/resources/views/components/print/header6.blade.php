<div class=" md:scroll-mt-20 sm:py-0 rounded-lg bg-white dark:border-gray-600 flex-1">
    <div class="border-b p-0 font-medium flex items-center justify-left {{$class}}">
        @php
            $hw = $dimensionImg ? '' : "h-20 w-56";
            $dimensionImg = $dimensionImg ? $dimensionImg : "h-full w-full";
        @endphp
        @if(empty($itemsShow))
        <div>
            <div class="{{$hw}} ">
                <img alt="TLC Logo" src="{{$dataSource['company_logo']}}" class="{{$dimensionImg}} {{$classImg}}">
            </div>
        </div>
        <div class="text-sm font-normal">
            <b>{{$dataSource['company_name']}}</b>
            <br>{{$dataSource['company_address']}}
            <br>Tel: {{$dataSource['company_telephone']}}
            <br>Fax: {{$dataSource['company_fax']}}
            <br>Email: {{$dataSource['company_email']}} Website: {{$dataSource['company_website']}}
        </div>
        @elseif(in_array('logo', $itemsShow))
            <div>
                <div class="{{$hw}}">
                    <img alt="TLC Logo" src="{{$dataSource['company_logo']}}" class=" {{$dimensionImg}} {{$classImg}}">
                </div>
            </div>
        @elseif(in_array('website', $itemsShow))
            <div class="text-sm font-normal">
                <a href="{{$dataSource['company_website']}}">Website: {{$dataSource['company_website']}}</a>
            </div>      
        @endif
    </div>
</div>
