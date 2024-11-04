@php
    $isAdmin = App\Utils\Support\CurrentUser::isAdmin();
@endphp


<div class="flex justify-center">
    <div class=" {{$layoutStyle}} py-4" style="{{ $layoutStyle }}">
        <div class=" w-full items-center bg-white p-0 flex flex-col justify-between" >
            {{-- Show Setting Reports --}}
            @if ($isAdmin)
                <div class="no-print absolute">
                    <a title='Edit Report' class="block p-2" href="{{ route('rp_reports.edit', $report->id) }}" target="blank" >
                        <span class="inline-flex items-center rounded-md bg-purple-50 px-2   py-1 text-xs font-medium text-purple-700 ring-1 ring-inset ring-purple-700/10">
                            <i class="fa-solid fa-gear"></i>
                        </span>
                    </a>
                </div>
            @endif

            <!-- Head section with a border -->
            {{-- TOFIX --}}
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
                        {{-- No letter head --}}
                    @break
                @endswitch
            <!-- Main content area -->
            <div class="w-full items-center ">
                {{-- Blocks --}}
                <div class="{{-- container mx-auto --}}" title="{{ $content['name'] ?? null }}">
                    <div class="grid grid-cols-12 gap-4 px-8 pb-8">
                        <x-reports2.report-block :blockDetails="$blockDetails" :report="$report" :currentParams="$currentParams" />
                    </div>
                </div>

            </div>
            <!-- Footer section with a border -->
            {{-- TOFIX --}}
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
