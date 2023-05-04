<x-renderer.table tableName="{{$table01ROName}}" :columns="$readOnlyColumns" :dataSource="$dataSource" showNo="{{$showNo?1:0}}" footer="{{$tableFooter}}" noCss="{{$noCss}}" />

@if(!$readOnly)
    @if($createANewForm)
        <x-renderer.button type='success' href='{!!$href!!}' >Create a new {{strtoupper( Str::singular($tableName))}}</x-renderer.button>
    @endif

    @if(!empty($btnCmdSettings) && $btnCmdSettings['CallCommandButtonList'])
        {{-- @dump($btnCmdSettings) --}}
        <script>
            function create(params){
                // button.disabled=true
                const {title, url, inspTmplId,ownerId,prodOrderId} = params
                const data = {inspTmplId, ownerId, prodOrderId}
                toastr.info("Creating a new document by cloning a template...")
                $.ajax({
                    type: "POST",
                    url,
                    data,
                    success: ()=>{
                        toastr.success("Created successfully.")
                        location.reload()
                    },
                    error: ()=> {
                        console.log("Failed")
                    },
                })
            }
        </script>
        @php
            $templates = [];
            foreach($dataSource as $line){
                $templates[$line->getQaqcInspTmpl->id] = 1;
            }
            $templates = array_keys($templates);
            // dump($templates);
        @endphp
        @foreach($btnCmdSettings['CallCommandButtonList'] as $button)
            @php
                if(in_array($button['inspTmplId'], $templates)) continue;
                $button['ownerId'] = $userId;
                $button['prodOrderId'] = $entityId;
            @endphp
            <x-renderer.button onClick="create({{json_encode($button)}});this.disabled=true;" type='success' >{{$button['title']}}</x-renderer.button>
        @endforeach
    @endif
@endif