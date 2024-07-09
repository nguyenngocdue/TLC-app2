@once
<script>
    const superProps = @json($superProps);
    const superWorkflows = @json($superWorkflows);
</script>
@endonce

<main class="w-full flex-grow dark:bg-gray-700 no-print">
    <div class="w-full h-18 no-print">
        @if($visible)
        <div class="no-print flex bg-white dark:bg-gray-800 flex-wrap items-center justify-between h-full text-purple-600 dark:text-purple-300">
            <div class="w-full md:w-1/2 flex items-center">
                    <x-renderer.heading level=4 title="@yield('tooltip')">
                        @yield('title', 'Untitled') 
                    </x-renderer.heading>
                    @if(in_array($action,['edit']))
                        @hasSection('status')
                            <div class="ml-1">
                                <x-renderer.status>@yield('status','')</x-renderer.status>
                            </div>
                        @endif
                    @endif
                    <div class="px-2 text-black">
                        @yield('docId','')
                    </div>
            </div>
            <ul class="w-full md:w-1/2">
                <li class="relative">
                    <x-navigation.breadcrumb />
                </li>
            </ul>
        </div>
        @endif
    </div>
</main>