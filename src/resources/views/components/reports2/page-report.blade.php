<div class="flex justify-center bg-only-print">
    <div class="p-4"> <!-- Loại bỏ padding ngoài để tăng không gian cho content -->
        <div class="{{$layoutStr}} items-center bg-white p-0 flex flex-col"> <!-- Loại bỏ padding -->
            <!-- Header section with a border -->
            <div class="w-full border-2 border-gray-300 p-2 mb-1"> <!-- Giảm padding -->
                <h1 class="text-lg font-bold text-center">Header</h1>
            </div>

            <!-- Main content area -->
            <div class="flex-grow flex flex-col items-center justify-center space-y-1 border-2 border-gray-300"> <!-- Giảm space-y -->
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

            <!-- Footer section with a border -->
            <div class="w-full border-2 border-gray-300 p-2 mt-1"> <!-- Giảm padding -->
                <p class="text-sm text-center">Footer content goes here</p>
            </div>
        </div>
    </div>
</div>
