<div class="flex justify-end pt-1">
    @foreach($links as $value)
        @if ($value['href'])
        <div class="px-2 py-1 text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700 mr-1 my-2">
            <a class="text-lg text-blue-500 hover:text-gray-400" href="{{$value['href']}}">
                {!!$value['icon']!!}
                <span class="flex text-xs font-normal">{!! $value['title'] !!}</span>
            </a>
        </div>
        @elseif(isset($value['modePrint']))
        <div class="px-2 py-1 text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700 mr-1 my-2">
            <button class="text-lg text-blue-500 hover:text-gray-400" onclick="window.print();">
                {!!$value['icon']!!}
                <span class="flex text-xs font-normal">{!! $value['title'] !!}</span>
            </button>
        </div>
        @else
        <div class="px-2 py-1 text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700 mr-1 my-2">
            <button class="text-lg text-blue-500 hover:text-gray-400" id={{$value['id']}}>
                {!!$value['icon']!!}
                <span class="flex text-xs font-normal">{!! $value['title'] !!}</span>
            </button>
            <script>
                    const buttonExport = document.getElementById('export-pdf');
                    function generatePDF() {
                        // Choose the element that your content will be rendered to.
                        const element = document.getElementById('print-pdf-document');
                        const date =  new Date;
                        const dateFormat = date.getFullYear() + '-' + (date.getMonth()+1) + '-' + date.getDate();
                        const opt = {
                            margin:       0,
                            filename:     `{{$type}}_${dateFormat}.pdf`,
                            image:        { type: 'jpeg', quality: 0.98 },
                            html2canvas:  { scale: 2},
                            jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
                            };
                        // Choose the element and save the PDF for your user.
                        html2pdf().set(opt).from(element).save();
                        // Old monolithic-style usage:
                        // html2pdf(element, opt);
                    }
                    buttonExport.addEventListener('click', generatePDF);
            </script>
        </div>
        @endif
    
    @endforeach
</div>
    