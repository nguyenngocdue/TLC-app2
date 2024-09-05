@php
@endphp

<div class="flex justify-center">
    <div class=" {{$layoutStyle}} py-4">
        <div class=" w-full items-center bg-white p-0 flex flex-col justify-between" style="{{ $layoutStyle }}">
            <!-- Head section with a border -->
                @switch($letterHeadId)
                    @case (1)
                        <div class="self-start pt-4 px-4 py-2 w-full">
                            <x-reports2.report-letter-head-type1 />
                        </div>
                    @break
                    @case (2)
                        <div class="">
                            <x-reports2.report-letter-head-type2 />
                        <div>
                    @break
                    @default
                    @break
                @endswitch
            <!-- Main content area -->
            <div class="w-full items-center ">
                {{-- Blocks --}}
                <div class="{{-- container mx-auto --}}" title="{{ $content['name'] ?? null }}">
                    <div class="grid grid-cols-12 gap-4 p-8">
                        <x-reports2.report-block :blockDetails="$blockDetails" :report="$report" />
                    </div>
                </div>

            </div>
            <!-- Footer section with a border -->
            <div>
                @switch($letterFooterId)
                    @case (1)
                        <div class="w-full border-2 border-gray-300 p-2">
                            <x-reports2.report-letter-footer-type1 />
                        </div>
                    @break
                    @case (2)
                         <div class="w-full border-2 border-gray-300 p-2">
                            <x-reports2.report-letter-footer-type2 />
                        </div>
                    @break
                    @default
                    @break
                @endswitch
            </div>
        </div>
    </div>
</div>
