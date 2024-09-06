<x-renderer.card title="Create New">
    Name: <input id="txtName" class="{{$classList}}" />
    Reg No: <input id="txtRegNo" class="{{$classList}}" />
    Address: <input id="txtAddress" class="{{$classList}}" />

    <x-renderer.button click="createNew()" id="btnCreateNew" htmlType="button" class="mt-2" type='success'>Create New</x-renderer.button>
</x-renderer.card>