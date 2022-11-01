<div class="flex flex-col">
    @if ($action === "edit")
    <div class="grid grid-cols-5 gap-4 mb-1 p-1 bg-white border rounded-lg ">
        @if(isset($attachHasMedia[$colName]))
        @foreach($attachHasMedia as $key => $attach)
        @if ($key === $colName )
        @foreach($attach as $media)
        <div class=" relative h-full flex mx-1 flex-col items-center p-1 border rounded-lg border-gray-300 group/item overflow-hidden bg-white ">
            <span>
                <img class="border  border-gray-300 rounded-md h-full w-full object-cover hover:bg-slate-100" src="{{ $path.$media['url_thumbnail']}}" alt="{{$media['filename']}}" />
                <svg fill="#d11a2a" id="showpic_deleted_{{$media['id']}}" class="hidden absolute  top-[50%] left-[50%] translate-x-[-50%] translate-y-[-50%]" id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 105.16 122.88">
                    <defs>
                        <style>
                            .cls-1 {
                                fill-rule: evenodd;
                            }

                        </style>
                    </defs>
                    <title>delete</title>
                    <path class="cls-1" d="M11.17,37.16H94.65a8.4,8.4,0,0,1,2,.16,5.93,5.93,0,0,1,2.88,1.56,5.43,5.43,0,0,1,1.64,3.34,7.65,7.65,0,0,1-.06,1.44L94,117.31v0l0,.13,0,.28v0a7.06,7.06,0,0,1-.2.9v0l0,.06v0a5.89,5.89,0,0,1-5.47,4.07H17.32a6.17,6.17,0,0,1-1.25-.19,6.17,6.17,0,0,1-1.16-.48h0a6.18,6.18,0,0,1-3.08-4.88l-7-73.49a7.69,7.69,0,0,1-.06-1.66,5.37,5.37,0,0,1,1.63-3.29,6,6,0,0,1,3-1.58,8.94,8.94,0,0,1,1.79-.13ZM5.65,8.8H37.12V6h0a2.44,2.44,0,0,1,0-.27,6,6,0,0,1,1.76-4h0A6,6,0,0,1,43.09,0H62.46l.3,0a6,6,0,0,1,5.7,6V6h0V8.8h32l.39,0a4.7,4.7,0,0,1,4.31,4.43c0,.18,0,.32,0,.5v9.86a2.59,2.59,0,0,1-2.59,2.59H2.59A2.59,2.59,0,0,1,0,23.62V13.53H0a1.56,1.56,0,0,1,0-.31v0A4.72,4.72,0,0,1,3.88,8.88,10.4,10.4,0,0,1,5.65,8.8Zm42.1,52.7a4.77,4.77,0,0,1,9.49,0v37a4.77,4.77,0,0,1-9.49,0v-37Zm23.73-.2a4.58,4.58,0,0,1,5-4.06,4.47,4.47,0,0,1,4.51,4.46l-2,37a4.57,4.57,0,0,1-5,4.06,4.47,4.47,0,0,1-4.51-4.46l2-37ZM25,61.7a4.46,4.46,0,0,1,4.5-4.46,4.58,4.58,0,0,1,5,4.06l2,37a4.47,4.47,0,0,1-4.51,4.46,4.57,4.57,0,0,1-5-4.06l-2-37Z" />
                </svg>
            </span>
            <div class=" invisible flex justify-center hover:bg-[#00000080] group-hover/item:visible   before:absolute before:-inset-1  before:bg-[#00000080]">
                <a title="{{$media['filename']}}" href="{{$path.$media['url_media']}}" target='_blank' class="hover:underline text-blue-600 dark:text-blue-500 px-2 absolute  top-[50%] left-[50%] translate-x-[-50%] translate-y-[-50%] text-xs text-center w-full">{{$media['filename']}}</a>
                <button type="button" onclick="updateTextbox({{$media['id']}}, '{{$colName}}_deleted')" class="absolute bottom-[10%] text-[25px]">
                    <i class=" text-[#d11a2a] fas fa-trash  cursor-pointer"></i>
                </button>
            </div>
        </div>
        @endforeach
        @endif
        @endforeach
        @else
        <span class="block w-full text-sm text-blue-500 p-2.5   dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">No file selected</span>
        @endif
    </div>
    <input id="{{$colName}}_deleted" name="{{$colName}}_deleted" type="hidden" value="" class=' p-2.5  bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' type='text'>

    <input multiple class="  block w-full text-sm text-gray-900  p-2.5 rounded-lg bg-white border  border-gray-300 cursor-pointer dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 " id="multiple_files" type="file" name="{{$colName}}[]">

    @else
    <input multiple class=" block w-full text-sm text-gray-900 p-2.5 rounded-lg bg-white border  border-gray-300 cursor-pointer dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 " id="multiple_files" type="file" name="{{$colName}}[]">
    @endif
</div>

<script type="text/javascript">
    var objColName = {};

    function updateTextbox(id, colName) {
        var binIcon = document.getElementById("showpic_deleted_" + id)

        if (!Object.keys(objColName).includes(colName)) {
            objColName[colName] = []
            objColName[colName].push(id)
            binIcon.classList.remove("hidden")
        } else {
            if (objColName[colName].includes(id)) {
                const index = objColName[colName].indexOf(id);
                objColName[colName].splice(index, 1)
                binIcon.classList.toggle("hidden")
            } else {
                objColName[colName].push(id)
                binIcon.classList.remove("hidden")
            }
        }
        document.getElementById(colName).value = objColName[colName]
    }

</script>
