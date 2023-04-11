{{-- https://github.com/szimek/signature_pad --}}
@php $w=340; $h=140; /* ORI 220 x 90*/ @endphp 
<div id="div1{{$name}}" class="relative border border-gray-300 rounded-md w-[{{$w}}px] h-[{{$h}}px]">
    <button type="button" id="btnReset1" class="no-print w-10 h-10 top-1 right-2 absolute">
        <i class="text-red-700 fa-solid fa-xmark cursor-pointer text-lg"></i>
    </button>
    {!! $value_decoded !!}
</div>
<div id="div2{{$name}}" class="hidden signature-pad--body">
    <div class="relative border rounded-md border-gray-300">
        <button type="button" id="btnReset2" class="w-10 h-10 top-1 right-2 absolute">
            <i class="text-red-700 fa-solid fa-xmark cursor-pointer text-lg"></i>
        </button>
        <canvas width="{{$w}}" height="{{$h}}" id="canvas_{{$name}}"
         style="touch-action: none; user-select: none;" >
        </canvas>
    </div>
    <input name="{{$name}}" id="{{$name}}" value='{!! $value !!}' type="hidden">
</div>
<script>
    const canvas = document.querySelector("#canvas_{{$name}}");
    const signaturePad = new SignaturePad(canvas);

    $("#btnReset1").click(function(){
        $('#div2{{$name}}').show()
        $('#div1{{$name}}').hide()
        $("#{{$name}}").val('')
    })
    $("#btnReset2").click(function(){
        signaturePad.clear()
        $("#{{$name}}").val('')

    })

    signaturePad.addEventListener("endStroke", () => {
        // if (signaturePad.isEmpty()) {
            // alert("Please provide a signature first.");
        // } else {
            // const data = signaturePad.toData()
            // console.log(data)
            const svg = signaturePad._toSVG()
            console.log(svg)
            $("#{{$name}}").val(svg)
        // }
    });
</script>