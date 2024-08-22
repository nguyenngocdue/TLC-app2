<div class="flex justify-center">
    <div class="py-4">
        <div class=" items-center bg-white p-0 flex flex-col justify-between" style="{{ $layoutStyle }}">
            <!-- Head section with a border -->
            <div class="w-full border-2 border-gray-300 p-2 mb-1">
                @switch($letterHeadId)
                    @case (1)
                        <x-reports2.letter-head-report-type1 />
                    @break

                    @case (2)
                        <x-reports2.letter-head-report-type2 />
                    @break

                    @default
                        <x-reports2.letter-head-report-type1 />
                    @break
                @endswitch

            </div>

            <!-- Main content area -->
            <div class="w-full items-center border-2 border-gray-300">
                {{-- Blocks --}}
                <div class="container mx-auto p-5" title="{{ $content['name'] ?? null }}">
                    <div class="grid grid-cols-12 gap-4">
                        <x-reports2.report-block :blockDetails="$blockDetails" :report="$report" />
                    </div>
                </div>

            </div>

            <!-- Footer section with a border -->
            <div class="w-full border-2 border-gray-300 p-2">
                @switch($letterFooterId)
                    @case (1)
                        <x-reports2.letter-footer-report-type1 />
                    @break

                    @case (2)
                        <x-reports2.letter-footer-report-type2 />
                    @break

                    @default
                        <x-reports2.letter-footer-report-type1 />
                    @break
                @endswitch
            </div>
        </div>
    </div>
</div>
