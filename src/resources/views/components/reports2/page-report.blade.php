<div class="flex justify-center bg-only-print">
    <div class="md:px-4">
        <div class="{{$layoutStr}} items-center bg-white p-8 flex flex-col">
            <div class="w-full h-full">
                <!-- Header section with a border -->
                <div class="w-full border-2 border-gray-300 p-4 mb-2">
                    <h1 class="text-lg font-bold text-center">Header</h1>
                </div>

                <!-- Content section with a border and sub-sections in the corners -->
                <div class="flex-1 border-2 border-gray-300 p-4 flex flex-col justify-between">
                    <div class="flex justify-between w-full mb-2">
                        <div class="p-2 border-2 border-gray-200">Top Left</div>
                        <div class="p-2 border-2 border-gray-200">Top Right</div>
                    </div>
                    
                    <!-- Main content area -->
                    <div class="flex-grow flex flex-col items-center justify-center space-y-2">
                        <p class="text-base font-bold text-center">
                            This is the main content area. It fills most of the page and is designed to hold the bulk of your text or graphical information.
                        </p>
                        <div class="w-full flex flex-wrap justify-around items-center">
                            <p class="text-base">Main Content Area 1</p>
                            <p class="text-base">Main Content Area 2</p>
                            <p class="text-base">Main Content Area 3</p>
                            <p class="text-base">Main Content Area 4</p>
                            <p class="text-base">Main Content Area 5</p>
                            <p class="text-base">Main Content Area 6</p>
                            <p class="text-base">Main Content Area 7</p>
                            <p class="text-base">Main Content Area 8</p>
                            <p class="text-base">Main Content Area 9</p>
                            <p class="text-base">Main Content Area 10</p>
                        </div>
                    </div>
                    
                    <div class="flex justify-between w-full mt-2">
                        <div class="p-2 border-2 border-gray-200">Bottom Left</div>
                        <div class="p-2 border-2 border-gray-200">Bottom Right</div>
                    </div>
                </div>

                <!-- Footer section with a border -->
                <div class="w-full border-2 border-gray-300 p-4 mt-2">
                    <p class="text-sm text-center">Footer content goes here</p>
                </div>
            </div>
        </div>
    </div>
</div>
