<table class="border-none whitespace-nowrap w-full">
    @php
        $urlCurrent = route($type.'.show',$id);
    @endphp
    <tbody>
        <tr>
            <td align="left">
                <div class="h-20 w-56">
                    <img alt="TLC Logo" src="{{$dataSource['company_logo']}}" class="h-full w-full">
                </div>
            </td>
            <td class="text-sm">
                <b>{{$dataSource['company_name']}}</b>
                <br>{{$dataSource['company_address']}}
                <br>Tel: {{$dataSource['company_telephone']}} 
                <br>Fax: {{$dataSource['company_fax']}}
                <br>Email: {{$dataSource['company_email']}} Website: {{$dataSource['company_website']}}</td>
                <td class="w-[20%]" align="center">
                    <div class="flex flex-row items-center justify-center gap-y-2 1ml-10">
                            <div id="{{$id}}" class="w-28 h-28 flex m-5"></div>
                            {{-- <div class="flex w-28 transform rotate-[270deg] float-right -ml-14 text-xs whitespace-pre-wrap">
                                @php
                                    $tableName = $type.'/'.$id;
                                    $tableName = join(' ',str_split($tableName, 16)); 
                                @endphp
                                {{$tableName}}
                            </div> --}}
                    </div>
                    @if($nameRenderDocId)
                    <div class="flex border border-gray-600 p-1 text-sm mb-1">
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