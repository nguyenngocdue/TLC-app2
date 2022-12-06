@php
// dd($name, $type, $readonly);
// dd($ownerDB->name)
@endphp
<x-renderer.card>
    <div class="grid grid-cols-12 gap-2 flex-nowrap ">
        <div class="col-span-4">
            <div class="border rounded-lg w-full border-gray-300 p-0.5 ">
                <x-renderer.avatar-name title="{{$ownerDB->name}}" description="{{$ownerDB->position_rendered}}" href="http://www.google.com">?</x-renderer.avatar-name>
                <input name="{{$ownerDB->name}}" class='hidden bg-white border border-gray-300 text-gray-900  rounded-lg  p-2.5   dark:placeholder-gray-400   w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none  focus:shadow-outline-purple focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input' type='text'>
            </div>
        </div>
        <div class="col-span-4">
            <input name="{{$name}}" value="{{$ownerDB->position_rendered}}" class='bg-white border border-gray-300 text-gray-900  rounded-lg  p-2.5   dark:placeholder-gray-400  block w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none  focus:shadow-outline-purple focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input' type='text'>
        </div>
        <div class="col-span-4">
            <div class="relative">
                <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <input datepicker type="text" value="2020-12-12 12:00" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date">
            </div>
        </div>
    </div>
    <div class="pt-2">
        <label for="" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Comment</label>
        <textarea name="" rows="10" class="bg-white border border-gray-300 text-gray-900 rounded-lg p-2.5 dark:placeholder-gray-400 block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="Type here..."></textarea>
    </div>
</x-renderer.card>
