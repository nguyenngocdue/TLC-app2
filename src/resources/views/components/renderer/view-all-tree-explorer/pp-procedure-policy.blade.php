<div class="grid grid-cols-12 gap-2 p-0">
    <div id="pdfViewer" class="col-span-8 border rounded" style="background-color: #393C3E">
        <embed id="pdfEmbed" src="" type="application/pdf" width="100%" height="100%" />
        {{-- <iframe id="pdfEmbed" src="" type="application/pdf" width="100%" height="600px" ></iframe> --}}        
    </div>
    <div class="col-span-4 border rounded bg-blue-100 p-2">
        <x-renderer.card title="Owning Department">
            Accounting             
        </x-renderer.card>
        
        <x-renderer.card title="Published Version">
            <x-renderer.view-all-tree-explorer.pp-procedure-policy-published-version
                ppId="{{$ppId}}"
                versionId="{{$versionId}}"
                updatePPRoute="{{$updatePPRoute}}"
                :versions="$versions"
                />
        </x-renderer.card>
        <x-renderer.card title="Notify To">
            <x-renderer.view-all-tree-explorer.pp-procedure-policy-notify-to
                ppId="{{$ppId}}"
                notifyToId="{{$notifyToId}}"
                updatePPRoute="{{$updatePPRoute}}"
                loadDynamicNotifyToTree="{{$loadDynamicNotifyToTree}}"
                :notifyTo="$notifyTo"
                />
        </x-renderer.card>
    </div>
</div>