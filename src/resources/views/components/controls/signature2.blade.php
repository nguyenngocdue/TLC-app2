{{-- https://github.com/szimek/signature_pad --}}
@php $w=340; $h=140; /* ORI 220 x 90*/ @endphp 
@if($value_decoded !== '')
    <div id="div1{{$name}}" class="relative border border-gray-300 rounded-md w-[{{$w}}px] h-[{{$h}}px]">
        @if($updatable)
            <button type="button" id="btnReset1_{{$count}}" class="no-print w-10 h-10 top-1 right-2 absolute">
                <i class="text-red-700 fa-solid fa-xmark cursor-pointer text-lg"></i>
            </button>
        @endif
        {!! $value_decoded !!}
    </div>
@endif

<div id="div2{{$name}}" class="{{$value_decoded == '' ? "" : "hidden"}} signature-pad--body">
    <div class="relative border rounded-md border-gray-300">
        <button type="button" id="btnReset2_{{$count}}" class="w-10 h-10 top-1 right-2 absolute">
            <i class="text-red-700 fa-solid fa-xmark cursor-pointer text-lg"></i>
        </button>
        <canvas width="{{$w}}" height="{{$h}}" id="canvas_{{$name}}"
         style="touch-action: none; user-select: none;" >
        </canvas>
    </div>
    <input name="{{$name}}" id="{{$name}}" value='{!! $value !!}' class="border w-full" type="{{$input_or_hidden}}">
</div>

@once
<script>
function registerSignature(name, count){
    const signaturePad = new SignaturePad(getEById("canvas_"+name)[0])
    $("#btnReset1_" + count).click(function(){
        // console.log("Reset 1 clicked")
        getEById('div2'+name).show()
        getEById('div1'+name).hide()
        getEById(name).val('')
        // $('#div2'+name).show()
        // $('#div1'+name).hide()
        // $("#"+name).val('')
    })
    $("#btnReset2_" + count).click(function(){
        // console.log("Reset 2 clicked")
        signaturePad.clear()
        getEById(name).val('')
        // $("#"+name).val('')

    })

    signaturePad.addEventListener("endStroke", () => {
        // if (signaturePad.isEmpty()) {
            // alert("Please provide a signature first.");
        // } else {
            // const data = signaturePad.toData()
            // console.log(data)
            const svg = signaturePad._toSVG()
            // console.log(svg)
            getEById(name).val(svg)
            // $("#"+name).val(svg)
        // }
    });
}
</script>
@endonce

<script>
    registerSignature("{{$name}}", "{{$count}}")
</script>