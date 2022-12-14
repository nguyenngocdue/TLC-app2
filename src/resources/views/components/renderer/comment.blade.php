@php
$content = $data['content'] ??'';
$authorName = $user->name;
$position = $user->position_rendered;
$path = env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/';

$date = $action === 'create' ? $data['created_at'] : $data['updated_at'] ?? '';
$timestamp = strtotime($date);
$time = $action === 'create' ? $date : date("d/m/Y H:i:s", $timestamp);
@endphp

<div id="fillColor_{{$data['id']}}" class="p-4 bg-gray-250 border rounded-lg shadow-md">
    {{-- @dump($data, $destroyable) --}}
    <div class="grid grid-cols-12 gap-2 flex-nowrap ">
        <div class="col-span-11 flex-nowrap ">
            <div class="grid grid-cols-12 gap-2 flex-nowrap ">
                <div class="col-span-4">
                    <div class="border bg-[#f5f5f5]  rounded-lg w-full border-gray-300 p-1 ">
                        <x-renderer.avatar-name title="{{$authorName}}" href="http://www.google.com">?</x-renderer.avatar-name>
                        <input name="owner_id" value="{{Auth::user()->id}}" class='hidden' type='text'>
                    </div>
                </div>
                <div class="col-span-3">
                    <input name='position_rendered' value="{{$position}}" class='bg-white bg-inherit  border border-gray-300 text-gray-900  rounded-lg  p-2.5   dark:placeholder-gray-400  block w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none  focus:shadow-outline-purple focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input' type='text'>
                </div>

                <div class="col-span-3">
                    <div class="relative  ">
                        <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <input datepicker type="text" value="{{$time}}" readonly class="bg-[#f5f5f5]  border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="">
                    </div>
                </div>
                <div class="col-span-2">
                    <input value="#:{{$data['id']}}" readonly class='bg-[#f5f5f5]  text-center  border border-gray-300 text-gray-900  rounded-lg  p-2.5   dark:placeholder-gray-400  block w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none  focus:shadow-outline-purple focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input' type='text'>
                </div>
                <div class="col-span-12 mt-2 rounded-lg border border-gray-300 overflow-hidden">
                    <textarea name="hasComment_{{$name}}" id="fillColor_{{$data['id']}}" rows="2" @readonly($data['readonly']) class=" {{$data['readonly'] ? 'bg-white ' : ''}} bg-inherit resize-none  text-gray-900  p-2.5 dark:placeholder-gray-400 block w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="Type here...">{{old('hasComment_'.$name, $content) ?? $content }}</textarea>
                    {{-- @dump() --}}
                    <x-renderer.smart-attachment readonly="{{$data['readonly']}}" destroyable={{false}} attCategory={{$name}} :attachmentData="$attachmentData" colName={{$name}} action={{$action}} labelName={{$labelName}} path={{$path}} />
                </div>
            </div>
        </div>
        @if($destroyable)
        <div class="col-span-1 m-auto text-center ">
            <button type="button" onclick="updateTxtboxComment({{$data['id']}}, 'comment__deleted_{{$data['id']}}')" class=" w-10 h-10 m-auto hover:bg-slate-300 rounded-full">
                <i class=" text-[#d11a2a] fas fa-trash  cursor-pointer"></i>
            </button>
            <input id="comment__deleted_{{$data['id']}}" name="comment__deleted_{{$data['id']}}" value="" class=' {{$showToBeDeleted ? "" : "hidden"}} p-2.5 bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' type='text'>
        </div>
        @endif

    </div>
</div>


<script type="text/javascript">
    var objColName = {};

    function updateTxtboxComment(id, commentDel) {

        var fillColor = document.getElementById("fillColor_" + id)
        console.log(fillColor)

        if (!Object.keys(objColName).includes(commentDel)) {
            objColName[commentDel] = []
            objColName[commentDel].push(id)
            fillColor.classList.remove("bg-gray-250")
            fillColor.classList.remove("bg-white")
            fillColor.classList.toggle("bg-[#f5f5f5]")
        } else {
            if (objColName[commentDel].includes(id)) {
                const index = objColName[commentDel].indexOf(id);
                objColName[commentDel].splice(index, 1)
                fillColor.classList.toggle("bg-[#f5f5f5]")

            } else {
                objColName[commentDel].push(id)
                fillColor.classList.toggle("bg-[#f5f5f5]")
            }
        }
        document.getElementById(commentDel).value = objColName[commentDel]
    }

</script>
