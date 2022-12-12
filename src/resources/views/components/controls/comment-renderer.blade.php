<x-renderer.card style=" border-gray-300 rounded-lg mt-2">
    @foreach($dataComment as $key => $value)
    {{-- @dump($value) --}}
    <x-renderer.comment labelName="{{$labelName}}" btnAtt="{{$value['btnAtt'] ?? false}}" name="{{$name}}" type="{{$type}}" id="{{$id}}" :dataComment="$value" action={{$action}} readonly="{{true}}"></x-renderer.comment>

    <button type="button" onclick="updateTextbox({{$value['id']}}, 'comment__deleted_{{$value['id']}}')" class="w-10 h-10  m-auto  hover:bg-slate-300 rounded-full">
        <i class=" text-[#d11a2a] fas fa-trash  cursor-pointer"></i>
    </button>

    <input id="comment__deleted_{{$value['id']}}" name="comment__deleted_{{$value['id']}}" value="" class=' p-2.5  bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' type='text'>
    @endforeach
</x-renderer.card>


<script type="text/javascript">
    var objColName = {};

    function updateTextbox(id, commentDel) {

        if (!Object.keys(objColName).includes(commentDel)) {
            objColName[commentDel] = []
            objColName[commentDel].push(id)
        } else {
            if (objColName[commentDel].includes(id)) {
                const index = objColName[commentDel].indexOf(id);
                objColName[commentDel].splice(index, 1)
            } else {
                objColName[commentDel].push(id)
            }
        }
        document.getElementById(commentDel).value = objColName[commentDel]
    }

</script>
