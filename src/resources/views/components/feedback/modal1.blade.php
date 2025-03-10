@props(['id'])
<!-- Modal backdrop. This what you want to place close to the closing body tag -->
<div
  id="{{$id}}"
  x-show="isModalOpenArray['{{$id}}']"
  x-transition:enter="transition ease-out duration-300"
  x-transition:enter-start="opacity-0"
  x-transition:enter-end="opacity-100"
  x-transition:leave="transition ease-in duration-300"
  x-transition:leave-start="opacity-100"
  x-transition:leave-end="opacity-0"
  class="fixed inset-0 z-30 hidden items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"
>
<!-- Modal -->
<div
  x-show="isModalOpenArray['{{$id}}']"
  x-transition:enter="transition ease-out duration-300"
  x-transition:enter-start="opacity-0 transform translate-y-1/2"
  x-transition:enter-end="opacity-100"
  x-transition:leave="transition ease-in duration-300"
  x-transition:leave-start="opacity-100"
  x-transition:leave-end="opacity-0  transform translate-y-1/2"
  @click.away="closeModal('{{$id}}')"
  @keydown.escape="closeModal('{{$id}}')"
  class="w-full px-6 py-4 overflow-hidden bg-white rounded-t-lg dark:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl"
  role="dialog"
  id="modal"
>
  <!-- Remove header if you don't want a close icon. Use modal body to place modal tile. -->
  <header class="flex justify-end">
    <button
      class="inline-flex items-center justify-center w-6 h-6  transition-colors duration-300 rounded dark:hover:text-gray-200 hover: hover:text-gray-700"
      aria-label="close"
      @click="closeModal('{{$id}}')"
    >{!! $closeIcon !!}</button>
  </header>
  <!-- Modal body -->
  <div class="mt-4 mb-6">
    <!-- Modal title -->
    <p class="mb-2 text-lg font-semibold text-gray-700 dark:text-gray-300">{{$title}}</p>
    <!-- Modal description -->
    <p class="text-sm text-gray-700 dark:text-gray-300">
      {!! $content !!}
    </p>
  </div>
  <footer class="flex flex-col items-center justify-end px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50 dark:bg-gray-800">
    <button @click="closeModal('{{$id}}')" class="w-full px-5 py-3 text-sm font-medium leading-5 text-gray-700 transition-colors duration-300 border border-gray-300 rounded-lg dark:text-gray-300 sm:px-4 sm:py-2 sm:w-auto active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray">
      Cancel
    </button>
    <button class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-300 bg-purple-600 border border-transparent rounded-lg sm:w-auto sm:px-4 sm:py-2 active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
      OK
    </button>
  </footer>

</div>
</div>
<!-- End of modal backdrop -->