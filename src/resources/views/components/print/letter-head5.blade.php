<table class="border-none whitespace-nowrap w-full">
    @php
        $urlCurrent = route($type.'.show',$id);
    @endphp
    <tbody>
        <tr>
            <td style="width: 30%;">
                <img alt="TLC Logo" src="{{$dataSource['company_logo']}}" class="mx-auto w-11/12">
            </td>
            <td style="width: 50%;" class="text-sm-vw">
                <b>{{$dataSource['company_name']}}</b>
                <br>{{$dataSource['company_address']}}
                <br>Tel: {{$dataSource['company_telephone']}} 
                <br>Fax: {{$dataSource['company_fax']}}
                <br>Email: {{$dataSource['company_email']}} - Website: {{$dataSource['company_website']}}
            </td>
            <td style="width: 20%">
                <div class="flex flex-row items-center justify-center">
                    <div id="{{$id}}" class="flex w-7/12"></div>
                </div>
                @if($nameRenderDocId)
                <div class="flex border border-gray-600 text-sm-vw m-1vw">
                    Doc ID: {{$nameRenderDocId}}
                </div>
                @endif
                
            </td>
        </tr>
    </tbody>
</table>

<script>
new QRCode(document.getElementById("{{$id}}"),"{{$urlCurrent}}",)
</script>