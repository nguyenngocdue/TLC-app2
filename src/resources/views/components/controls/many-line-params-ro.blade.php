<x-renderer.table tableName="{{$table01ROName}}" :columns="$readOnlyColumns" :dataSource="$dataSource" showNo="{{$showNo?1:0}}" footer="{{$tableFooter}}" noCss="{{$noCss}}" />

@if(!$readOnly)
    @if($createANewForm)
        <x-renderer.button type='success' href='{!!$href!!}' >Create a new {{strtoupper( Str::singular($tableName))}}</x-renderer.button>
    @endif

    @if(!empty($btnCmdSettings) && $btnCmdSettings['CallCommandButtonList'])
        @dump($btnCmdSettings)
        <script>
            function create(params, button){
                button.disabled=true
                const {title, url, inspTmplId,ownerId,prodOrderId} = params
                const data = {inspTmplId, ownerId, prodOrderId}
                $.ajax({
                    type: "POST",
                    url,
                    data,
                    success: ()=>console.log(111),
                    error: ()=>console.log(222),
                })
            }
        </script>
        @foreach($btnCmdSettings['CallCommandButtonList'] as $button)
            @php
                $button['ownerId'] = $userId;
                $button['prodOrderId'] = $entityId;
            @endphp
            <x-renderer.button onClick="create({{json_encode($button)}}, this)" type='success' >{{$button['title']}}</x-renderer.button>
        @endforeach
    @endif
@endif