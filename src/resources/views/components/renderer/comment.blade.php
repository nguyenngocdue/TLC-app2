@php
$content = $data['content'] ??'';
$authorName = $user->name;
$position = $user->position_rendered;
$path = env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/';
$isReadOnly = $data['readonly'];
$isEmptyContent = $content === '';
$isDestroyable = $action === 'create';

$date = $action === 'create' ? $data['created_at'] : $data['updated_at'] ?? '';
$time = $date ? date("d/m/Y H:i:s", strtotime($date)) : date("d/m/Y H:i:s");
@endphp

<div id="fillColor_{{$data['id']}}" class="p-4 bg-gray-250  border rounded-lg shadow-md ">
    <div class="grid grid-cols-12 gap-2 flex-nowrap">
        <div class=" grid col-span-9  text-center flex-nowrap">
            <div class="grid grid-cols-12 gap-4 ">
                <div class="col-span-4">
                    <div class="dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 border cursor-pointer bg-[#f5f5f5] thumbs rounded-lg w-full border-gray-300 p-1 focus:border-purple-400 focus:outline-none  ">
                        <x-renderer.avatar-name title="{{$authorName}}"></x-renderer.avatar-name>
                        <input name="owner_id" value="{{Auth::user()->id}}" class='hidden' type='text'>
                    </div>
                </div>
                <div class="col-span-4 ">
                        <input name='position_rendered' value="{{$position}}" readonly class='dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 bg-[#f5f5f5] border border-gray-300 text-gray-900  rounded-lg  p-2.5  dark:placeholder-gray-400  block w-full text-sm  focus:border-purple-400 focus:outline-none ' type='text'>
                </div>

                <div class="col-span-4">
                    <div class="relative    ">
                        <div class="flex absolute  inset-y-0 left-0 items-center pl-3 pointer-events-none">
                            <i class="fa-duotone fa-calendar"></i>
                        </div>
                        <input datepicker type="text" value="{{$time}}" readonly class="dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 bg-[#f5f5f5] border border-gray-300 text-gray-900 text-sm rounded-lg focus:border-purple-400 focus:outline-none block w-full pl-8 p-2.5" placeholder="">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-span-3  text-center flex">
            <div class="col-span-1 flex-1 ">
                <input value="#{{$data['id']}}" readonly class='dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 bg-[#f5f5f5] text-center  border border-gray-300 text-gray-900  rounded-lg  p-2.5   dark:placeholder-gray-400  block w-full text-sm   focus:border-purple-400 focus:outline-none  ' type='text'>
            </div>
            @if($destroyable)
            <div class=" m-auto text-center flex-1">
                <div class="flex ">
                    <button type="button" onclick="updateTxtboxComment({{$data['id']}}, 'comment_deleted_{{$data['id']}}')" class=" w-10 h-10 m-auto hover:bg-slate-300 rounded-full">
                        <i class=" text-[#d11a2a] fas fa-trash  cursor-pointer"></i>
                    </button>
                    <input id="comment_deleted_{{$data['id']}}" name="comment_deleted_{{$data['id']}}" readonly value="" class=' {{$showToBeDeleted ? "" : "hidden"}}  p-2.5 w-full   bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:border-purple-400 focus:outline-none  focus:shadow-outline-purple focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray ' type='text'>
                </div>
            </div>
            @endif
        </div>
        <div class="col-span-12 mt-2 rounded-lg border border-gray-300 overflow-hidden   ">
            <textarea name="newComment_{{$name}}" rows="2" @readonly($data['readonly']) placeholder="{{$isReadOnly ? '': 'Type here...'}}" class=" {{$isReadOnly && $isEmptyContent  ? 'bg-white hidden ' : ''}} bg-inherit resize-none  text-gray-900  p-2.5 dark:placeholder-gray-400 block w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray">{{old('newComment_'.$name, $content) ?? $content }}</textarea>
            <x-renderer.attachment readonly="{{$data['readonly']}}" destroyable={{$isDestroyable}} categoryName={{$name}} :attachmentData="$attachmentData" action={{$action}} labelName={{$labelName}} path={{$path}} />
        </div>
    </div>
</div>

{{-- @include('components.feedback.alert-validation') --}}

@once
<script type="text/javascript">
    var objColName = {};

    function updateTxtboxComment(id, commentDel) {

        var fillColor = document.getElementById("fillColor_" + id)
        const overrideColor = document.querySelectorAll(".override_fillColor_" + id)
        console.log(fillColor, overrideColor)

        if (!Object.keys(objColName).includes(commentDel)) {
            objColName[commentDel] = []
            objColName[commentDel].push(id)
            fillColor.classList.remove("bg-gray-250")
            fillColor.classList.toggle("bg-red-100")
        } else {
            if (objColName[commentDel].includes(id)) {
                const index = objColName[commentDel].indexOf(id);
                objColName[commentDel].splice(index, 1)

            } else {
                objColName[commentDel].push(id)
            }
            fillColor.classList.toggle("bg-red-100")
        }
        document.getElementById(commentDel).value = objColName[commentDel]
    }

</script>
@endonce
