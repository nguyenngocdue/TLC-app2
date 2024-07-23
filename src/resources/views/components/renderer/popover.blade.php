<div data-popover id="{{$id}}" role="tooltip" class="{{$hidden ? 'hidden' : ''}} absolute z-10 invisible inline-block text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 w-auto dark:text-gray-400 dark:bg-gray-800 dark:border-gray-600">
    @if($title)
    <div class="px-3 py-2 bg-gray-100 border-b border-gray-200 text-center rounded-t-lg dark:border-gray-600 dark:bg-gray-700">
        <h3 class="font-semibold text-gray-900 dark:text-white">{{$title}}</h3>
    </div>
    @endif
    @if($content)
    <div class="p-0">
        {!!$content!!}
    </div>
    @endif
    <div data-popper-arrow></div>
</div>
{{-- <script>
    window.onload = function () { const anchorTags = document.querySelectorAll('a'); anchorTags.forEach(function(a){a.addEventListener('click', function(ev){ev.preventDefault();})}); const dropdownEl = document.querySelector('[data-dropdown-toggle]'); if (dropdownEl) {dropdownEl.click();} const modalEl = document.querySelector('[data-modal-toggle]'); if(modalEl) {modalEl.click(); }  const dateRangePickerEl = document.querySelector('[data-rangepicker] input'); if (dateRangePickerEl) { dateRangePickerEl.focus(); } const drawerEl = document.querySelector('[data-drawer-show]'); if (drawerEl) { drawerEl.click(); } }
</script> --}}
