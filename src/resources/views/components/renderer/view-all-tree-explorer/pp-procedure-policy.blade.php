<div class="grid grid-cols-12 gap-2 p-2">
    <div id="pdfViewer" class="col-span-8 border rounded bg-pink-100">
        PDF Viewer
    </div>
    <div class="col-span-4 border rounded bg-blue-100 p-2">
        <x-renderer.card title="Owning Department">
            Accounting
        </x-renderer.card>
        
        <x-renderer.card title="Published Version">
            <button class="px-2 mx-2 bg-blue-500 text-white rounded">
                <i class="fa fa-upload"></i> Upload File...
            </button>
            <table class="w-full">
                <tr>
                    <th>Published</th>
                    <th>File Name</th>
                    <th>Delete</th>
                </tr>
                @foreach($versions as $version)
                    <tr>
                        <td class="text-center">
                            <input class="p-1 m-2 cursor-pointer hover:underline" name="published_version" type="radio" class=""/> 
                        </td>
                        <td class="p-1 cursor-pointer hover:bg-blue-200 rounded">
                            <span class="" onclick="$('#pdfViewer').html('this action will load file: {{$version['fileName']}}')">
                                {{ $version['fileName'] }}
                                <br/>
                                <span class="flex gap-2">
                                    <img class="w-6 h-6 rounded-full" src="{{$version['avatar']}}" />
                                    {{$version['uploaded_by']}}
                                    ({{$version['uploaded_at']}})
                                </span>
                            </span>
                            <span>
                            </span>
                        </td>
                        <td>
                            <button class="px-2 mx-2 bg-red-500 text-white rounded"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                @endforeach            
            </table>
        </x-renderer.card>
        <x-renderer.card title="Notify To">
            <x-renderer.view-all-tree-explorer.pp-procedure-policy-notify-to
                :notifyToId="$notifyToId"
                :notifyToHodExcluded="$notifyToHodExcluded"
                :notifyToMemberExcluded="$notifyToMemberExcluded"
                :notifyTo="$notifyTo"
                :notifyToTree="$notifyToTree"
                />
        </x-renderer.card>
    </div>
</div>