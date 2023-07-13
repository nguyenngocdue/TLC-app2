<div id="{{$id}}" class=" md:scroll-mt-20 sm:py-0 rounded-lg bg-white dark:border-gray-600">
    <div class="border-b p-0 font-medium flex items-center justify-between">
        @php
            $content = strtoupper($name);
            $urlCurrent = url()->current();
        @endphp
        @if($tableOfContents)
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
                
                <div class="flex flex-row items-center justify-end gap-y-2 ml-10">
                        <div id="{{$qrId}}" class="w-28 h-28 flex m-5"></div>
                        <div class="flex w-28 transform rotate-[270deg] float-right -ml-14 text-xs whitespace-nowrap">
                            @php
                                $tableName = $type .'/'.$qrId;
                                $tableName = join(' ',str_split($tableName, 16)); 
                            @endphp
                            {{$tableName}}
                        </div>
                </div>
            <script>
                new QRCode(document.getElementById("{{$qrId}}"),"{{$urlCurrent}}",)
            </script>
            
        @else
        <div class="flex flex-1 justify-center mt-3">
            @php
                $page = $page ? '('. $page .')' : '';
            @endphp
            <x-renderer.heading level=5>{{$content.' '. $page}}</x-renderer.heading>
        </div>
        @endif
    </div>
    @if($tableOfContents)
    <div class="flex flex-1 justify-center border-b">
        <x-renderer.heading level=5>{{strtoupper($nameRenderOfPageInfo)}}</x-renderer.heading>
    </div>
    @endif
    <div class="flex justify-between py-1 px-3">
        {!!$contentHeader!!}
        @if(!$tableOfContents)
        <div class="w-48">
            <img src="{{asset('logo/tlc.png')}}" />
        </div>
        @endif
    </div>
    @if(!$tableOfContents)
        @if($consentNumber)
        <div class="border-t py-1 px-3 font-medium flex justify-start">
            <div class="flex">
                <div class="flex flex-col pr-2  font-medium">
                    <span>Consent Number:</span>
                </div>
                <div class="flex flex-col font-normal">
                    <span>{{$consentNumber}}</span>
                </div>
            </div>
        </div>
        @endif
    @else
    <div class="border-t py-1 px-3">
        <div class="flex flex-1 justify-center pr-2  font-medium">
            <span>TABLE OF CONTENTS </span>
        </div>
    </div>
    @endif
</div>
