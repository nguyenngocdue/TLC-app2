<table class="border-none whitespace-nowrap w-full">
    @php
        $urlCurrent = url()->current();
    @endphp
    <tbody>
        <tr>
            <td align="left">
                <img alt="TLC Logo" width1="245" height1="80" src="{{$dataSource['company_logo']}}" class="w-full pl-5">
            </td>
            <td align="center"></td>
            <td class="text-sm">
                <b>{{$dataSource['company_name']}}</b>
                <br>{{$dataSource['company_address']}}
                <br>Tel: {{$dataSource['company_telephone']}} Fax: {{$dataSource['company_fax']}}
                <br>Email: {{$dataSource['company_email']}} Website: {{$dataSource['company_website']}}</td>
                <td class="w-[20%]" align="center">
                    <div class="flex flex-row items-center justify-center gap-y-2">
                            <div id="{{$id}}" class="w-28 h-28 flex m-5"></div>
                            <div class="flex w-28 transform rotate-[270deg] float-right -ml-14 text-xs whitespace-pre-wrap">
                                {{$type}} /{{$id}}
                            </div>
                    </div>
                    <div class="flex flex-row flex-wrap gap-y-0 mb-1 mr-1">
                        <div class="border border-gray-600 p-1">
                            Doc ID: TLC-HLC-ECO-0083
                        </div>
                    </div>
            </td>
        </tr>
    </tbody>
</table>

<script>
new QRCode(document.getElementById("{{$id}}"),"{{$urlCurrent}}",)
</script>