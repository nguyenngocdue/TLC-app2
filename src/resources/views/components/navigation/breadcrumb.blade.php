<div class="flex justify-end pt-1">
    @foreach($links as $value)
        @if (isset($value['href']))
        <div class="{{$classList}}">
            <a class="text-lg text-blue-500 hover:text-gray-400" href="{{$value['href']}}">
                {!!$value['icon']!!}
                <span class="flex text-xs font-normal">{!! $value['title'] !!}</span>
            </a>
        </div>
        @elseif(isset($value['type']) && $value['type'] == 'modePrint')
        <div class="{{$classList}}">
            <button class="text-lg text-blue-500 hover:text-gray-400" onclick="window.print();">
                {!!$value['icon']!!}
                <span class="flex text-xs font-normal">{!! $value['title'] !!}</span>
            </button>
        </div>
        @elseif(isset($value['type']) && $value['type'] == 'report' )
        <div class="{{$classList}}">
            <button class="text-lg text-blue-500 hover:text-gray-400" data-dropdown-toggle="dropdownDelay" data-dropdown-delay="500" data-dropdown-trigger="hover" >
                {!!$value['icon']!!}
                <span class="flex text-xs font-normal">{!! $value['title'] !!}</span>
            </button>
            <div id="dropdownDelay" class="bg-gray-50 z-10 hidden divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDelayButton">
                    @foreach($value['dataSource'] as $reportType)
                        @foreach( $reportType as $report)
                            <li title="{{$report['mode']}}">
                                <a href="{{$report['href']}}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">{{$report['title']}}</a>
                            </li>
                        @endforeach
                  @endforeach
                </ul>
            </div>
        </div>
        @else
        Unknown type {{$value['type'] }}
        {{-- <div class="{{$classList}}">
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
        </div> --}}
        @endif
    
    @endforeach
</div>
    