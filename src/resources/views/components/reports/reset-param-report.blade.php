<form action="{{$route}}" method="post" class="m-0">
    @method('PUT')
    @csrf
    <input type="hidden" name='_entity' value="{{ $entity }}">
    <input type="hidden" name='action' value="updateReport{{$typeReport}}">
    <input type="hidden" name='type_report' value="{{$typeReport}}">
    <input type="hidden" name='mode_name' value="{{$modeName}}">


    <button type="submit" class=" text-gray-900 bg-orange-400 focus:shadow-outline border border-gray-200 focus:outline-none hover:bg-purple-400  font-medium rounded-lg text-sm px-4 py-2 text-center inline-flex items-center dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
        <i class="fa-sharp fa-solid fa-circle-xmark"></i>
        <span class="ml-2">Reset Filter</span>
    </button>
</form>
