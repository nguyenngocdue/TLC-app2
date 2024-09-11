@extends('layouts.app')

@section('topTitle', 'Permission Library')
@section('title', 'Set Roles to Rolesets')

@section('content')
<main class="h-full overflow-y-auto">
    <div class="container mx-auto grid px-6">
        <label for="roleSets" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Select an
            option role sets</label>
        <div class="mb-3 grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            <form action="{{ route('setroles.store') }}" method="POST">
                @csrf
                <select id="roleSets" class="select2-hidden-accessible" onchange="this.form.submit()" style="width: 100%;" name="roleSet_id" tabindex="-1" aria-hidden="true">
                    @foreach($roleSets as $roleSet)
                    <option value="{{$roleSet->id}}" @selected($roleSetSelected ? $roleSetSelected->id == $roleSet->id : null) >{{$roleSet->name ?? $roleSet->id}}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="mb-8 rounded-lg bg-white px-6 py-4 shadow-md dark:bg-gray-800">
            <div class="w-full overflow-x-auto">
                <form action="{{ route('setroles.store2') }}" method="POST">
                    @csrf
                    @foreach ($lastRoleNames as $value)
                    @php
                        $invalidEntity = !in_array($value, $entities);
                        $classBg = $invalidEntity ? 'bg-red-200 border p-4' : 'bg-white border p-4';
                    @endphp
                    <x-renderer.card title="{{ $value }}" class="{{$classBg}}">
                        {{-- <span class="my-4 rounded-full bg-green-100 px-2 py-1 text-base font-medium leading-tight text-green-700 dark:bg-green-700 dark:text-green-100"></span> --}}
                        <div class="grid grid-cols-3">
                            @foreach ($roles as $role)
                            @php
                            $var = explode('-', $role->name);
                            $last = end($var);
                            @endphp
                            @if ($last === $value)
                            <div class="form-check items-center" title="{{ $role->name }}">
                                <input type="hidden" name="roleSet_id" value="{{ $selected }}">
                                <label class="text-sm font-normal text-gray-900 dark:text-gray-300">
                                    <input type="checkbox" name="checked[]" value="{{ $role->id }}" title="{{ $role->name }}" @isset($roleUsing) @foreach ($roleUsing as $item) @if ($item->id == $role->id)
                                    @checked(true)
                                    @endif
                                    @endforeach
                                    @endisset
                                    class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                                    {{ $role->name }}
                                </label>
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </x-renderer.card>
                    @endforeach
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