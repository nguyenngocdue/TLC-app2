<button class="px-2 mx-2 bg-blue-500 text-white rounded">
    <i class="fa fa-upload"></i> Upload File...
</button>
@if($versions->count())
    <table class="w-full">
        <tr>
            <th>File Name</th>
            <th>Published</th>
            <th></th>
        </tr>
        @foreach($versions as $version)
            <tr>
                <td class="p-1 cursor-pointer hover:bg-blue-200 rounded">
                    <span class="" onclick="loadPDF('{{$version['src']}}')">
                        {{ $version['fileName'] }}
                        <br/>
                        <span class="flex gap-2">
                            <img class="w-6 h-6 rounded-full" src="{{$version['avatar']}}" />
                            {{$version['uploaded_by']}}
                            ({{$version['uploaded_at']}})
                        </span>
                    </span>                                
                </td>
                <td class="text-center">
                    <input class="p-1 m-2 cursor-pointer hover:underline" name="published_version" type="radio" class=""/> 
                </td>
                <td>
                    <button class="px-2 mx-2 bg-red-500 text-white rounded"><i class="fa fa-trash"></i></button>
                </td>
            </tr>
        @endforeach            
    </table>
@endif