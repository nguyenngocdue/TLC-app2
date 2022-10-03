@extends('layouts.app')
@section('content')
    <main class="h-full overflow-y-auto">
        <div class="container mx-auto grid px-0">
            <div
                class="focus:shadow-outline-purple my-4 flex items-center justify-between rounded-lg bg-purple-600 p-3 text-base font-semibold text-purple-100 shadow-md focus:outline-none">
                <div class="flex items-center">
                    <svg class="mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                        </path>
                    </svg>
                    <span>Set Role->RoleSet</span>
                </div>
                <span>View more →</span>
            </div>
            <label for="roleSets" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-400">Select an
                option role sets</label>
            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                <form action="{{ route('setrolesets.update', $id) }}" method="POST">
                    @method('PUT')
                    @csrf
                    <select name="roleSet" id="roleSets"
                        class="role block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
                        <option value="none" {{ count($roleSetUsing) == 0 ? 'selected' : '' }}>None</option>
                        @foreach ($roleSets as $roleSet)
                            <option value="{{ $roleSet->name }}"
                                @isset($roleSetUsing)
                                    @foreach ($roleSetUsing as $item)
                                        @if ($item->name == $roleSet->name)
                                            @selected(true)
                                        @endif
                                    @endforeach            
                                @endisset>
                                {{ $roleSet->name }} </option>
                        @endforeach
                    </select>
                    <button
                        class="focus:shadow-outline-purple my-2 ml-2 rounded-lg border border-transparent bg-emerald-500 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 hover:bg-emerald-200 focus:outline-none active:bg-emerald-600"
                        type="submit">Save Change</button>
                </form>
            </div>
        </div>
    </main>
@endsection
