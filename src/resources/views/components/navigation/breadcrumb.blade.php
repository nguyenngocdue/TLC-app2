<ol class="flex text-[#00000073] ">
    <a class="text-blue-500 hover:text-gray-400" href="{{ route($type . '_viewall.index') }}">View All</a>
    <span class="px-1">/</span>
    @if($first_id)
    <a class="text-blue-500 hover:text-gray-400" href="{{ route($type . '_edit.edit', $first_id) }}">View First</a>
    <span class="px-1">/</span>
    <a class="text-blue-500 hover:text-gray-400" href="{{ route($type . '_edit.edit', $latest_id) }}">View Latest</a>
    <span class="px-1">/</span>
    @endif
    <a class="text-blue-500 hover:text-gray-400" href="{{ route($type . '_addnew.create') }}">Add New</a>
    <span class="px-1">/</span>
    <a class="text-blue-500 hover:text-gray-400" href="{{ route($singular . '_mngprop.index')}}">Props</a>
    <span class="px-1">/</span>
    <a class="text-blue-500 hover:text-gray-400" href="{{ route($singular .'_mngrls.index') }}">Relationships</a>
    <span class="px-1">/</span>
    <a class="text-blue-500 hover:text-gray-400" href="{{ route($singular .'_mngstt.index') }}">Statuses</a>
</ol>