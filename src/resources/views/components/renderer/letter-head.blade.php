<table class="border-none whitespace-nowrap w-full">
    @php
        $urlCurrent = url()->current();

    @endphp
    <tbody>
        <tr>
            <td align="left">
                <img alt="TLC Logo" src="{{$dataSource['company_logo']}}" class="w-52 pr-5">
            </td>
            <td align="center"></td>
            <td class="text-sm">
                <b>{{$dataSource['company_name']}}</b>
                <br>{{$dataSource['company_address']}}
                <br>Tel: {{$dataSource['company_telephone']}} Fax: {{$dataSource['company_fax']}}
                <br>Email: {{$dataSource['company_email']}} Website: {{$dataSource['company_website']}}</td>
                <td class="w-[20%]" align="center">
                    <div class="flex flex-row flex-wrap justify-center gap-y-0">
                        <div class="relative max-w-full min-h-[1px]">
                            {{-- <div class="float-left transform rotate-[270deg] mt-8 ml-8">
                            </div> --}}
                            <div id="{{$id}}" class="w-24 h-24 flex"></div>
                            <script>
                                new QRCode(document.getElementById("{{$id}}"),"{{$urlCurrent}}",)
                            </script>
                            <div class="flex transform rotate-[270deg] float-right mt-8 -ml-4">
                                eco/64388
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-row flex-wrap gap-y-0">
                        <div class="border border-gray-400">
                            Doc ID: TLC-HLC-ECO-0083
                        </div>
                    </div>
            </td>
        </tr>
    </tbody>
</table>