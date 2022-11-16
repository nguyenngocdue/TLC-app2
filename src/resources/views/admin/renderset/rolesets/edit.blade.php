@extends('layouts.app')
@section('title', 'Set Roles')
@section('content')
<main class="h-full overflow-y-auto">
    <div class="container mx-auto grid px-6">
        <div class="mb-8 rounded-lg bg-white px-4 py-3 text-center shadow-md dark:bg-gray-800">
            <label for="roleSets" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-400">Select an
                option Role Set</label>
            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                <form action="{{ route('setrolesets.update', $id) }}" method="POST">
                    @method('PUT')
                    @csrf
                    <select name="roleSet" id="roleSets" onclick="event.preventDefault()" class="role block w-full rounded-lg border border-gray-300 bg-gray-100 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
                        <option value="none" {{ count($roleSetUsing) == 0 ? 'selected' : '' }}>None</option>
                        @foreach ($roleSets as $roleSet)
                        <option value="{{ $roleSet->name }}" @isset($roleSetUsing) @foreach ($roleSetUsing as $item) @if ($item->name == $roleSet->name)
                            @selected(true)
                            @endif
                            @endforeach
                            @endisset>
                            {{ $roleSet->name }}
                        </option>
                        @endforeach
                    </select>
                    <button class="focus:shadow-outline-purple my-2 ml-2 rounded-lg border border-transparent bg-emerald-500 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 hover:bg-purple-400 focus:outline-none active:bg-emerald-600" type="submit">Save Change</button>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection