<!-- Trigger Button -->
<div class="flex justify-center mt-10">
    <x-renderer.button id="openModal" htmlType="submit" type="danger">
        <i class="text-red-200 fa-solid fa-bomb"></i> SQL Error
    </x-renderer.button>
</div>

<!-- Modal -->
<div id="modal" class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center hidden z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-3/4 max-h-screen relative">
        <!-- Close Button (X) in Top-Right Corner -->
        <button id="closeModalTopRight" class="absolute top-4 right-4 text-gray-500 hover:text-red-600">
            <i class="fa-sharp fa-solid fa-xmark text-2xl"></i>
        </button>
        
        <h2 class="text-xl font-semibold text-red-600">
            Error <i class="text-red-600 fa-solid fa-bomb"></i>
        </h2>
        <!-- Updated Message Box -->
        <div class="mt-4 bg-black text-green-500 whitespace-pre-wrap overflow-y-auto max-h-[500px] p-4 rounded-lg border-2 shadow-lg shadow-slate-400">
            {!! $message !!}
        </div>
        <div class="mt-6 flex">
            <x-renderer.button id="closeModal" htmlType="submit" type="secondary" class="pr-4">
                <i class="fa-sharp fa-solid fa-circle-xmark pr-1"></i> Close
            </x-renderer.button>
            <span class="pr-2"> </span> 
            <x-renderer.button id="openBlock" htmlType="submit" type="link" class="" href={{$btnHref}} target="_blank">
                <i class="fa-solid fa-forward"></i> Open Block
            </x-renderer.button>
        </div>
    </div>
</div>

<!-- Script to handle pop-up functionality -->
<script>
    const modal = document.getElementById('modal');
    const openModalButton = document.getElementById('openModal');
    const closeModalButton = document.getElementById('closeModal');
    const closeModalTopRightButton = document.getElementById('closeModalTopRight');

    // Show the modal
    openModalButton.addEventListener('click', () => {
        modal.classList.remove('hidden');
    });

    // Hide the modal
    closeModalButton.addEventListener('click', () => {
        modal.classList.add('hidden');
    });

    // Hide the modal with top-right "X" button
    closeModalTopRightButton.addEventListener('click', () => {
        modal.classList.add('hidden');
    });

    // Close modal when clicking outside modal content
    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    });

    // Close modal when pressing 'Esc' key
    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            modal.classList.add('hidden');
        }
    });
</script>
