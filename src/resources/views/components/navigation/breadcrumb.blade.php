<div class="flex justify-end">
    @foreach($links as $value)
        @if (isset($value['href']))
        <div class="{{$classList}}">
            <a class="text-lg text-blue-500 hover:text-gray-400" target="{{isset($value['target'])?$value['target']:''}}" href="{{$value['href']}}">
                {!!$value['icon']!!}
                <span class="hidden sm:flex text-xs font-normal">{!! $value['title'] !!}</span>
            </a>
        </div>
        @elseif(isset($value['type']))
            @switch($value['type'])
                @case('modePrint')
                <div class="{{$classList}}">
                    <button class="text-lg text-blue-500 hover:text-gray-400" onclick="window.print();">
                        {!!$value['icon']!!}
                        <span class="hidden sm:flex text-xs font-normal">{!! $value['title'] !!}</span>
                    </button>
                </div>
                @break
                @case('modal')
                    <div class="{{$classList}}">
                        <button class="text-lg text-blue-500 hover:text-gray-400 focus:outline-none" 
                            @click="toggleModal('{{$value['modalId']}}')"
                            @keydown.escape="closeModal('{{$value['modalId']}}')"
                            >
                            {!!$value['icon']!!}
                            <span class="hidden sm:flex text-xs font-normal">{!! $value['title'] !!}</span>
                        </button>
                    </div>
                    {!! $value['modalBody'] !!}
                @break
                @case('selectDropdown')
                    <div class="{{$classList}}">
                        <button class="text-lg flex items-center sm:block text-blue-500 hover:text-gray-400" data-dropdown-toggle="a" data-dropdown-delay="500" data-dropdown-trigger="click" >
                            {!!$value['icon']!!}
                            <span class="flex sm:hidden"><i class="fa-solid text-sm fa-chevron-down pl-1"></i></span>
                            <span class="hidden sm:flex text-xs font-normal">
                                <span>{!! $value['title'] !!}</span>
                                <i class="fa-solid fa-chevron-down pl-1"></i>
                            </span>
                        </button>
                        <x-renderer.select-dropdown name="a" id="a" :dataSource="$value['dataSource']" />
                    </div>
                @break
                @default
                    Unknown type {{$value['type'] }} 
                @break
            @endswitch
        @endif
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
    @endforeach
</div>
    