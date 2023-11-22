{{-- https://github.com/szimek/signature_pad --}}
@php $w=340; $h=140; /* ORI 220 x 90*/ @endphp 
@if($value_decoded !== '')
<div id="div1{{$name}}" class="relative border rounded-md border-gray-300 w-[340px] h-[140px]">
    @if($updatable)
    <div class="text-left">Please sign here:</div>
            <button type="button" id="btnReset1_{{$count}}" class="no-print w-10 h-10 top-1 right-2 absolute">
                <i class="text-red-700 fa-solid fa-xmark cursor-pointer text-lg"></i>
            </button>
        @endif
        {!! $value_decoded !!}
    </div>
    <p class="text-left">{!! $signatureComment !!}</p>
@endif

<div id="div2{{$name}}" class="{{$value_decoded == '' ? "" : "hidden"}} signature-pad--body">
    <div class="relative border rounded-md border-gray-300 w-[340px] h-[140px]">
        @if($updatable)
        <div class="text-left">Please sign here:</div>
            <button type="button" id="btnReset2_{{$count}}" class="w-10 h-10 top-1 right-2 absolute">
                <i class="text-red-700 fa-solid fa-xmark cursor-pointer text-lg"></i>
            </button>
        @endif
        <canvas width="{{$w}}" height="{{$h}}" id="canvas_{{$name}}" style="touch-action: none; user-select: none;" ></canvas>
    </div>
    <div class="text-left">Comment:</div>
    @if(!is_null($signatureCommentColumnName))
    {{$debug ? $signatureCommentColumnName : ""}}
    <input type="text" class="border-2 rounded w-full" 
            name="{{$signatureCommentColumnName}}" 
            id="{{$signatureCommentColumnName}}" 
            value="{{$signatureComment}}" 
            placeholder="Input your comment here..."    
    />
    @endif

    {{$debug ? $name : ""}}
    <input type="{{$input_or_hidden}}" class="border-2 rounded w-full" name="{{$name}}" id="{{$name}}" value='{!! $value !!}' />

    {{$debug ? $categoryColumnName : ""}}
    <input type="{{$input_or_hidden}}" class="border-2 rounded w-full" name="{{$categoryColumnName}}" id="{{$categoryColumnName}}" value='{!! $category !!}' />

    {{$debug ? $signableTypeColumnName : ""}}
    <input type="{{$input_or_hidden}}" class="border-2 rounded w-full" name="{{$signableTypeColumnName}}" id="{{$signableTypeColumnName}}" value='{!! $signableType !!}' />

    {{$debug ? $ownerIdColumnName : ""}}
    <input type="{{$input_or_hidden}}" class="border-2 rounded w-full" name="{{$ownerIdColumnName}}" id="{{$ownerIdColumnName}}" value='{!! $signedPersonId !!}'/>
</div>

@once
<script>
function registerSignature(name, count, ownerIdColumnName, cuid){
    const signaturePad = new SignaturePad(getEById("canvas_"+name)[0])
    $("#btnReset1_" + count).click(function(){
        // console.log("Reset 1 clicked")
        getEById('div2'+name).show()
        getEById('div1'+name).hide()
        getEById(name).val('')
        getEById(ownerIdColumnName).val('')
        // $('#div2'+name).show()
        // $('#div1'+name).hide()
        // $("#"+name).val('')
    })
    $("#btnReset2_" + count).click(function(){
        // console.log("Reset 2 clicked")
        signaturePad.clear()
        getEById(name).val('')
        getEById(ownerIdColumnName).val('')
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
            getEById(ownerIdColumnName).val(cuid)
            // $("#"+name).val(svg)
        // }
    });
}
</script>
@endonce

<script>
    registerSignature("{{$name}}", "{{$count}}", "{{$ownerIdColumnName}}", "{{$cuid}}")
</script>