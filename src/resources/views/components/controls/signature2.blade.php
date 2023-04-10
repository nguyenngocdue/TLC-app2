{{-- https://github.com/szimek/signature_pad --}}
<div id="div1{{$name}}" class="relative border border-gray-300 rounded-md w-[664px] h-[339px]">
    <button type="button" id="btnReset1" class="w-10 h-10 top-1 right-2 absolute">
        <i class="text-red-700 fa-solid fa-xmark cursor-pointer text-lg"></i>
    </button>
    {!! $value_decoded !!}
</div>
<div id="div2{{$name}}" class="hidden signature-pad--body">
    <div class="relative border rounded-md border-gray-300">
        <button type="button" id="btnReset2" class="w-10 h-10 top-1 right-2 absolute">
            <i class="text-red-700 fa-solid fa-xmark cursor-pointer text-lg"></i>
        </button>
        <canvas width="664" height="339" id="canvas_{{$name}}"
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