@extends('layouts.app')

@section('topTitle', 'Permission Library')
@section('title', 'Set Roles')

@section('content')
<main class="h-full overflow-y-auto">
    <div class="container mx-auto grid px-6">
        <div class="mb-8 rounded-lg bg-white px-4 py-3 text-center shadow-md dark:bg-gray-800">
            <label for="roleSets" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Select an
                option Role Set</label>
            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                <form action="{{ route('setrolesets.update', $id) }}" method="POST">
                    @method('PUT')
                    @csrf
                    <select id="roleSets" class="select2-hidden-accessible" onchange="this.event.preventDefault()" style="width: 100%;" name="roleSet_id" tabindex="-1" aria-hidden="true">
                        <option value="none" @selected($roleSetUsing == null ? true : null)>None</option>
                        @foreach($roleSets as $roleSet)
                        <option value="{{$roleSet->id}}" @selected($roleSetUsing ? $roleSetUsing->id == $roleSet->id : null) >{{$roleSet->name ?? $roleSet->id}}</option>
                        @endforeach
                    </select>
                    <button class="focus:shadow-outline-purple my-2 ml-2 rounded-lg border border-transparent bg-emerald-500 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 hover:bg-purple-400 focus:outline-none active:bg-emerald-600" type="submit">Save Change</button>
                </form>
            </div>
        </div>
    </div>
</main>
<script>
    $('[id="'+"roleSets"+'"]').select2({
        placeholder: "Please select..."
        , allowClear: false
        , templateResult: select2FormatOption
    });
</script>
@endsection