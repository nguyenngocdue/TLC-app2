{{-- https://github.com/szimek/signature_pad --}}
<div id="div1{{$name}}" class="border border-gray-300 rounded-md w-[664px] h-[339px]">
    {!! $value_decoded !!}
    <x-renderer.button class="mt-7" id="btnReset1">Reset</x-renderer.button>
</div>
<div id="div2{{$name}}" class="hidden signature-pad--body">
    <canvas width="664" height="339" id="canvas_{{$name}}"
    class="border rounded-md border-gray-300" style="touch-action: none; user-select: none;" ></canvas>
    <x-renderer.button id="btnReset2">Reset</x-renderer.button>
    <input name="{{$name}}" id="{{$name}}" value="{!! $value !!}" type="hidden">
</div>

<script>
    const canvas = document.querySelector("#canvas_{{$name}}");
    const signaturePad = new SignaturePad(canvas);

    $("#btnReset1").click(function(){
        console.log('aaa')
        $('#div1{{$name}}').hide()
        $('#div2{{$name}}').show()
        $("#{{$name}}").val('')
    })
    $("#btnReset2").click(function(){
        console.log('bbb')
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