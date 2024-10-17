<li class="flex p-1 hover:bg-gray-200 {{$selectedProjectId == $project->id ? 'bg-blue-200 ' : ''}}">
    <form action="{{$route}}" method="POST" class='flex mb-0'>
        @csrf
        @method('PUT')
        <input type="hidden" name="action" value="updateGlobal">
        <input type="hidden" name="selected-project-id" value="{{$project->id}}">
        <button>
        <x-renderer.avatar-item
            title="{{$project->name}}"
            description="{{$project->description}}"
            avatar="{{$project->src}}"
            shape="round"
            />
        </button>
    </form>
</li>