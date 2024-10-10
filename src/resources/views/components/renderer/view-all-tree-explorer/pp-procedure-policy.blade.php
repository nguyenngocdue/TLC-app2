<div class="grid grid-cols-12 gap-2 p-2">
    <div id="pdfViewer" class="col-span-8 border rounded bg-pink-100">
        PDF
    </div>
    <div class="col-span-4 border rounded bg-blue-100 p-2">
        <x-renderer.card title="Owning Department">
            Accounting
        </x-renderer.card>
        <x-renderer.card title="Notify To">
            <x-renderer.view-all-tree-explorer.pp-procedure-policy-notify-to
                :notifyTo="$notifyTo"
                />
        </x-renderer.card>
        <x-renderer.card title="Published Version">
            @foreach(["test01.pdf", "test02.pdf", "test03.pdf"] as $fileName)
                    {{-- <label class="p-1 m-2 cursor-pointer rounded hover:bg-blue-200"> --}}
                        
                    <input class="p-1 m-2 cursor-pointer hover:underline" name="published_version" type="radio" class=""> 
                        <span class="p-1 m-2 cursor-pointer hover:bg-blue-200 rounded" onclick="$('#pdfViewer').html('loading {{$fileName}}')">
                            {{ $fileName }}
                        </span>
                    </input>
                        {{-- <a href="#" class="text-blue-500 hover:text-blue-700"></a> --}}
                    {{-- </label> --}}
                    <br/>
            @endforeach            
        </x-renderer.card>
                
    </div>
</div>