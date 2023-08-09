<div class=" md:scroll-mt-20 sm:py-0 rounded-lg bg-white dark:border-gray-600">
    <div class="border-b p-0 font-medium flex items-center justify-left">
        @php
        @endphp
        <div>
            <div class="h-20 w-56">
                <img alt="TLC Logo" src="{{$dataSource['company_logo']}}" class="h-full w-full">
            </div>
        </div>
        <div class="text-sm font-normal">
            <b>{{$dataSource['company_name']}}</b>
            <br>{{$dataSource['company_address']}}
            <br>Tel: {{$dataSource['company_telephone']}}
            <br>Fax: {{$dataSource['company_fax']}}
            <br>Email: {{$dataSource['company_email']}} Website: {{$dataSource['company_website']}}
        </div>
    </div>
</div>
