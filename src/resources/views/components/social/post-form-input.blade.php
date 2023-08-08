@extends("modals.modal-small")
@section($modalId.'-header', "Create new Post")
@section($modalId.'-body')
        <div class="post-avatar-user flex space-x-2 items-center mb-1">
            <div class="relative">
                <img
                    src="{{asset('images/avatar.jpg')}}"
                    alt="Profile picture"
                    class="w-10 h-10 rounded-full cursor-pointer"
                />
                <span class="bg-green-500 w-3 h-3 rounded-full absolute right-0 top-3/4 border-white border-2"></span>
            </div>
            <div>
                <div class="font-semibold cursor-pointer">
                    Foden Ngo
                </div>
                <span class="text-sm text-gray-500 dark:text-gray-400 border border-gray-800 rounded-2xl py-0 px-1">
                    <i class="fa-sharp fa-light fa-earth-americas"></i>
                    public
                </span>    

            </div>
            
        </div>
        <hr/>
        <div class="mt-1">
            <textarea id="new-post-textarea" name="post_content" type="text" class="block w-full h-28 p-4 pl-2 text-sm text-gray-900 border border-white rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
            placeholder="What's on your mind?"></textarea>
            
        </div>
        <div class="flex space-x-3">
            <button class="py-1 px-2 bg-gray-300 rounded">
                <i class="fa-thin fa-face-smile"></i>
            </button>
            <input id="post-file" name="post_files[]" type="file" multiple class="py-1 px-2 bg-gray-300 rounded w-32" />
        </div>
        <div>
            <div id="preview" class="flex">
            </div>
        </div>
    @endsection
    
    @section($modalId.'-footer')
    <div class="flex items-center justify-end rounded-b border-t border-solid border-slate-200 dark:border-gray-600 p-2">
        <x-renderer.button click="closeModal('{{$modalId}}')">Cancel</x-renderer.button>
        <x-renderer.button htmlType="submit" class="mx-1" type='success'>Create Post</x-renderer.button>
    </div>
    @endsection
    
    @section($modalId.'-javascript')
    <script>
        // $(document).ready(function(){
        //     $('#form-new-post').on('submit',function(e){
        //         e.preventDefault();
        //         var content = $("#new-post-textarea").val();
        //         var files = $("#post-file")[0].files;
        //         console.log(files)
        //         if(content || files.length > 0){
        //             $.ajax({
        //             type: 'post',
        //             url: '/posts',
        //             data: new FormData(this),
        //             processData: false,
        //             contentType: false,
        //             success: (response) => {
        //                 toastr.success(response.message);
        //             },
        //             error: (jqXHR) => {
        //                 toastr.error(jqXHR.responseJSON.message);
        //                 },
        //             }) 
        //         }
                
        //     })
        // })
    </script>
    @endsection
