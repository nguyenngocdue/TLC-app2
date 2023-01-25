<div class="grid gap-6 mb-8 md:grid-cols-2 ">
    <x-renderer.card title="Alerts">
        <x-feedback.alert type="success" message="Hello Success"></x-feedback.alert>
        <x-feedback.alert type="info" message="Hello Info"></x-feedback.alert>
        <x-feedback.alert type="warning" message="Hello Warning"></x-feedback.alert>
        <x-feedback.alert type="error" message="Hello Error"></x-feedback.alert>
        <br />
        <br />
        Empty attributes:
        <x-feedback.alert />
    </x-renderer.card>
    <x-renderer.card title="Modals">
        <div class="mb-8 grid gap-6 md:grid-cols-2 xl:grid-cols-2">
                
            <button @click="openModal('modal1')" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                Open Modal 1
            </button>
            <button @click="openModal('modal2')" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                Open Modal 2
            </button>
            <x-feedback.modal1 id="modal1" title="Hello 1" content="What is this"/>
            <x-feedback.modal1 id="modal2" title="Hello 2" content="I am good"/>
            <button @click="openModal('modal3')" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                Open Modal Extended 3
            </button>
            <button @click="openModal('modal4')" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                Open Modal Extended 4
            </button>
            <x-feedback.modal-extended id="modal3" title="Hello 3" content="What is this"/>
            <x-feedback.modal-extended id="modal4" title="Hello 4" content="I am good"/>
        </div>
    </x-renderer.card>
</div>