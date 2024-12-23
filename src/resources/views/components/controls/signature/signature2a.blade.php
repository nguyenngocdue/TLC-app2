{{-- https://github.com/szimek/signature_pad --}}
@php 
// $w=210; $h=138; /* ORI 220 x 90*/ 
$canvasBg = $readOnly ? 'bg-gray-200' : 'bg-white';
// dump($signatureId);
@endphp 
<div style="width:288px; aspect-ratio:288/144;">
{{-- <div style="width:210px; aspect-ratio:210/105;"> --}}
{{-- <div style="width:90%; aspect-ratio:210/105;"> --}}
    @if(!$readOnly)
    <div title="{{$title}}">Signature here:</div>
    @endif
    <div id="div1{{$name}}" class="relative border rounded h-full2">
        @if(!$readOnly)
            <button type="button" id="btnReset1_{{$count}}" class="no-print w-10 h-10 top-1 right-2 absolute">
                <i class="text-red-700 fa-solid fa-xmark cursor-pointer text-lg"></i>
            </button>
        @endif
        
        <canvas 
                width="288" height="144" 
                id="canvas_{{$id}}" 
                class="{{$canvasBg}} rounded w-full"
                style="touch-action: none; user-select: none;" ></canvas>
    </div>
    <input type="{{$input_or_hidden}}" class="border rounded w-full border-gray-200" name="{{$name}}" id="{{$name}}" value="{!! $value !!}" />
    @if($showCommentBox )
        @if($readOnly /*&& $commentValue*/)
            @if($commentValue)
            Comment:
            <div class="text-gray-500">{!! $commentValue !!}</div>
            @endif
        @else
        Comment:
        <textarea class="border border-gray-200 rounded w-full" rows="3" name="{{$commentName}}" id="{{$commentName}}" placeholder="Comment here...">{!! $commentValue !!}</textarea>
        @endif
    @endif
    @if($showDecisionBox)
    @php
    $class = [
            'approved' => 'peer-checked:bg-green-300 peer-checked:text-green-700',
            'rejected' => 'peer-checked:bg-pink-300 peer-checked:text-pink-700',
    ];
    $cursor = $readOnly ? "cursor-not-allowed" : "cursor-pointer";
    $selected = $decisionValue;
    @endphp
    <div class="flex gap-1 items-center">
        <div class="grid w-full text-xs grid-cols-2 space-x-2 rounded-xl border-2 border-gray-300 bg-gray-200 p-1 mt-1">
            @foreach(['approved'=>'APPROVE', 'rejected'=>'REJECT'] as $decisionId => $option)
                <div>
                    <input type="radio" 
                        name="{{$decisionName}}" 
                        id="{{$decisionName}}_{{$decisionId}}" 
                        class="peer hidden" 
                        @checked($selected==$decisionId)  
                        @disabled($readOnly)
                        value="{{$decisionId}}"
                        onclick="$('#actionButton_{{$signatureId}}').prop('disabled', false).addClass('bg-purple-700')"
                    />
                    <label for="{{$decisionName}}_{{$decisionId}}" 
                        class="{{$class[$decisionId]}} {{$cursor}} block select-none rounded-lg p-2 text-center peer-checked:font-bold 1peer-checked:text-white"
                        title="#{{$decisionId}}"
                        {{-- onclick="console.log(222)" --}}
                        >
                        {{$option}}
                    </label>
                </div>
            @endforeach
        </div>
        {{-- {{$readOnly?'disabled':'no-disabled'}} --}}
        {{-- <input 
            type="submit" 
            id="actionButton_{{$signatureId}}"
            name="actionButton" 
            value="SUBMIT" 
            disabled
            class="rounded-xl font-bold bg-purple-200 text-white p-4 cursor-pointer disabled:cursor-not-allowed 1disabled:bg-purple-200"
        /> --}}
        <div class="py-1">
            <input type="hidden" value="SUBMIT" name="actionButton" />
            <button 
                id="actionButton_{{$signatureId}}"
                {{-- name="actionButton" 
                value="SUBMIT"  --}}
                disabled
                class="rounded-xl h-8 font-bold border-2 border-purple-400 bg-purple-300 text-white px-2 cursor-pointer disabled:cursor-not-allowed"
            >
                <i class="fa-light fa-paper-plane"></i>
            </button>
        </div>
        {{-- <x-renderer.button name="actionButton" value="btnSubmitASignature" htmlType="submit" type="primary" class="rounded-xl" disabled="{{$readOnly}}" >SUBMIT</x-renderer.button> --}}
    </div>
    @endif
</div>

@once
<script>
const registerSignature = (id, name, count, readOnly, svgContent, lineId) => {
    const canvasId = "canvas_"+id
    // console.log(name, id, canvasId)
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
            if(lineId) getEById("inspector_id_" + lineId).val('')
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
    registerSignature('{{$id}}','{{$name}}', {{$count}}, {{$readOnly ? 'true' : 'false'}}, svgContent, "{{$lineId}}")
</script>