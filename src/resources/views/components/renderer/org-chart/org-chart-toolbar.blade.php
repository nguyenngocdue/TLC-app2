<div class="no-print">
    <form class="" method="POST" action="{{route('updateUserSettings')}}" >
        @method('PUT')
        @csrf
        <input type="hidden" name='action' value="updateShowOptionsOrgChart">
        <x-renderer.card title="">
        <div class="justify-between grid grid-cols-12 gap-5 items-center">
            {{-- <div class="col-span-4">
            <label for="">Workplace</label>
                <x-utils.filter-workplace tableName="workplaces" multiple="true" name="show_options[workplace][]" id="workplace" typeToLoadListener="" :selected="$showOptions['workplace'] ?? []"></x-utils.filter-workplace>
            </div> --}}
            <div class="col-span-4">
            <label for="">Head of Department</label>
            <x-utils.filter-head-of-department tableName="departments" name="show_options[department]" id="department" typeToLoadListener="" selected="{{$showOptions['department'] ?? ''}}"></x-utils.filter-head-of-department>
            </div>
            <div class="col-span-1"></div>
            <div class="col-span-4">
            <label for="">Show Options</label>
            <x-renderer.card title="" class="bg-white border p-2">
                <div class="justify-evenly flex">
                    <label>Only BOD
                        <x-renderer.editable.checkbox name="show_options[loadOnlyBod]" cell="{{$showOptions['loadOnlyBod'] ?? ''}}"></x-renderer.editable.checkbox>
                    </label>
                    <label class="ml-3">Workers
                    <x-renderer.editable.checkbox name="show_options[loadWorker]" cell="{{$showOptions['loadWorker'] ?? ''}}"></x-renderer.editable.checkbox>
                    </label>
                    <label class="ml-3">Resigned
                    <x-renderer.editable.checkbox name="show_options[loadResigned]" cell="{{$showOptions['loadResigned'] ?? ''}}"></x-renderer.editable.checkbox>
                    </label>
                </div>
            </x-renderer.card>
            </div>
            <div class="col-span-3 flex justify-end mt-5">
                <x-renderer.button htmlType='submit' type="secondary" class="w-20" >Update ORG Chart</x-renderer.button>
            </div>
        </div>
        </x-renderer.card>
    </form>
</div>