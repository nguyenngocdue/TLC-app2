<div class="flex justify-end pt-1">
    <div class="px-2 py-1 text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700 mr-1 my-2">
        <button id="export-pdf" class="text-lg text-blue-500 hover:text-gray-400">
            <i class="fa-solid fa-file-export"></i>
            <span class="flex text-xs font-normal">Export PDF</span>
        </button>
    </div>
    @foreach($links as $value)
    <div class="px-2 py-1 text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700 mr-1 my-2">
        <a class="text-lg text-blue-500 hover:text-gray-400" href="{{$value['href']}}">
            {!!$value['icon']!!}
            <span class="flex text-xs font-normal">{!! $value['title'] !!}</span>
        </a>
    </div>
    @endforeach
</div>
<script>
    const buttonExport = document.getElementById('export-pdf');
    function generatePDF() {
        // Choose the element that your content will be rendered to.
        const element = document.getElementById('invoice');
        // Choose the element and save the PDF for your user.
        html2pdf().from(element).save();
    }
    buttonExport.addEventListener('click', generatePDF);
</script>