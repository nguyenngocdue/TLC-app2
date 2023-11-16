{{-- https://github.com/szimek/signature_pad --}}
@php 
$w=340; $h=140; /* ORI 220 x 90*/ 
$canvasBg = $readOnly ? 'bg-gray-200' : 'bg-white'
@endphp 
<div class=" w-[340px]">
    Sign here:
    <div id="div1{{$name}}" class="relative border rounded h-[140px]">
        @if(!$readOnly)
            <button type="button" id="btnReset1_{{$count}}" class="no-print w-10 h-10 top-1 right-2 absolute">
                <i class="text-red-700 fa-solid fa-xmark cursor-pointer text-lg"></i>
            </button>
        @endif
        
        <canvas width="{{$w}}" height="{{$h}}" 
                id="canvas_{{$id}}" 
                class="{{$canvasBg}} rounded"
                style="touch-action: none; user-select: none;" ></canvas>
    </div>
    <input type="{{$input_or_hidden}}" class="border rounded w-full border-gray-200" name="{{$name}}" id="{{$name}}" value="{!! $value !!}" />
    @if($showCommentBox)
        Comment:
        @if($readOnly)
            @if($commentValue)
                {!! $commentValue !!}
            @else
                <div class="text-gray-500">(no comment)</div>
            @endif
        @else
        <textarea class="border border-gray-200 rounded w-full" rows="3" name="{{$commentName}}" id="{{$commentName}}" placeholder="Comment here...">{!! $commentValue !!}</textarea>
        @endif
    @endif
    @if($showDecisionBox)
    @php
    $class = [
            1 => 'peer-checked:bg-green-300 peer-checked:text-green-700',
            2 => 'peer-checked:bg-pink-300 peer-checked:text-pink-700',
    ];
    $cursor = $readOnly ? "cursor-not-allowed" : "cursor-pointer";
    $selected = $decisionValue;
    @endphp
    <div class="grid w-full grid-cols-2 space-x-2 rounded-xl bg-gray-200 p-2">
        @foreach([1=>'Approve', 2=>'Reject'] as $decisionId => $option)
            <div>
                <input type="radio" 
                    name="{{$decisionName}}" 
                    id="{{$decisionName}}_{{$decisionId}}" 
                    class="peer hidden" 
                    @checked($selected==$decisionId)  
                    @disabled($readOnly)
                    value="{{$decisionId}}"
                />
                <label for="{{$decisionName}}_{{$decisionId}}" 
                    class="{{$class[$decisionId]}} {{$cursor}} block select-none rounded-xl p-2 text-center peer-checked:font-bold 1peer-checked:text-white"
                    title="#{{$decisionId}}"
                    >{{$option}}</label>
            </div>
        @endforeach
    </div>
    @endif
</div>

@once
<script>
const registerSignature = (id, name, count, readOnly, svgContent) => {
    const canvasId = "canvas_"+id
    console.log(name, id, canvasId)
    const signaturePad = new SignaturePad(getEById(canvasId)[0])
    if(svgContent){
        var svgDataUrl = 'data:image/svg+xml;base64,' + btoa(svgContent);
        signaturePad.fromDataURL(svgDataUrl);
        // drawSvgInCanvas(canvasId, svgContent)
        signaturePad.off()
    }
    if(!readOnly){
        $("#btnReset1_" + count).click(()=>{
            signaturePad.clear()
            signaturePad.on()
            getEById(name).val('')
        })
        signaturePad.addEventListener("endStroke", () => {
            const svg = signaturePad._toSVG()
            getEById(name).val(svg)
        })
    }
}
</script>
@endonce

<script>
    svgContent = '{!! $value_decoded !!}'
    registerSignature('{{$id}}','{{$name}}', {{$count}}, {{$readOnly ? 'true' : 'false'}}, svgContent)
</script>