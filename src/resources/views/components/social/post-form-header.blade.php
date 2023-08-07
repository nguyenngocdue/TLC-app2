<div className="flex p-2 border-b border-gray-300 dark:border-dark-third space-x-4" style="display: flex;">
    <img src="{{asset('images/avatar.jpg')}}" alt="Profile picture" class="w-10 h-10 rounded-full cursor-pointer">
    <div    @click="toggleModal('{{$modalId}}')"
            @keydown.escape="closeModal('{{$modalId}}')" class="flex flex-1 bg-gray-100 rounded-full items-center justify-start pl-4 cursor-pointer dark:bg-gray-600 dark:text-gray-300 text-gray-500 text-lg">
        <p>What's on your mind , Foden Ngo ?</p>
    </div>
</div>