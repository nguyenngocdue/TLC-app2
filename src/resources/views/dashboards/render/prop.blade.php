@extends('layouts.app')
@section('content')
    <main class="h-full overflow-y-auto">
        <div class="container mx-auto grid px-0">
            {{-- <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
              avatars
      </h4>
      <form action="{{route($type.'_renderprop.update',$userLogin->id)}}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
           @php
            if(isset($userLogin)){
              $settingDatas = $userLogin->settings;
              $dataRender = array_diff_key($data,$settingDatas);
            }
           @endphp
            @foreach ($data as $key => $value)
             @if (array_key_exists($key, $dataRender))
              <div class="col-md-2 col-12">
                <label for="{{$key}}">{{ltrim($key,'_')}}</label>
                <input type="checkbox" class="checkbox-toggle" name="{{$key}}" checked>
              </div>
             @else
              <div class="col-md-2 col-12">
                <label for="{{$key}}">{{ltrim($key,'_')}}</label>
                <input type="checkbox" class="checkbox-toggle" name="{{$key}}">
              </div>
             @endif
            @endforeach
        </div>
        <button type="submit" class="btn btn-primary">Apply</button>
      </form> --}}
            <div
                class="focus:shadow-outline-purple my-4 flex items-center justify-between rounded-lg bg-purple-600 p-3 text-base font-semibold text-purple-100 shadow-md focus:outline-none">
                <div class="flex items-center">
                    <svg class="mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                        </path>
                    </svg>
                    <span>{{ ucfirst($type) }}</span>
                </div>
                <span>View more →</span>
            </div>
            <form action="{{ route($type . '_renderprop.index') }}" method="GET">
                <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                    <div>
                        <input type="text" name="search"
                            class="block w-full rounded-md border border-slate-300 bg-white px-3 py-2 placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm"
                            value="{{ $search }}">
                    </div>
                    <div>
                        <button type="submit"
                            class="focus:shadow-outline-purple rounded-lg border border-transparent bg-emerald-500 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 hover:bg-emerald-200 focus:outline-none active:bg-emerald-600">Search</button>
                    </div>
                </div>
            </form>
            <div class="mt-2 mb-8 w-full overflow-hidden rounded-lg border shadow-sm">
                <div class="w-full overflow-x-auto">
                    <table class="whitespace-no-wrap w-full">
                        <thead>
                            <tr
                                class="border-b bg-gray-50 text-left text-xs font-semibold tracking-wide text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                                <th class="px-4 py-3">Action</th>
                                @foreach ($data as $key => $value)
                                    @if ($value['hidden'] === null)
                                        <th class="{{ $key . '_th' }} px-4 py-3">{{ $value['label'] }}</th>
                                    @endif
                                @endforeach
                                @foreach ($data2 as $key => $value)
                                    @if ($value['hidden'] === null)
                                        <th class="{{ $key . '_th' }} px-4 py-3">{{ $value['label'] }}</th>
                                    @endif
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y bg-white dark:divide-gray-700 dark:bg-gray-800">
                            @if (isset($data))
                                @foreach ($users as $key => $user)
                                    <tr class="text-gray-700 dark:text-gray-400">
                                        <td class="px-4 py-3 text-center text-sm">
                                            <button
                                                class="focus:shadow-outline-gray btn-delete-user rounded-lg px-2 py-2 text-sm font-medium leading-5 text-red-600 focus:outline-none dark:text-red-400"
                                                data-url="{{ route($type . '_manage.destroy', $user->id) }}"​
                                                type="button"><i class="fas fa-trash"></i></button>
                                        </td>
                                        @foreach ($data as $key1 => $value)
                                            @if ($value['hidden'] === null)
                                                @if ($value['column_name'] === 'id')
                                                    {{-- {{dd($type.'_edit.update')}} --}}
                                                    <td class="px-4 py-3 text-sm">
                                                        <a
                                                            href="{{ route($type . '_edit.update', $user[$value['column_name']]) }}">{{ $user[$value['column_name']] }}</a>
                                                    </td>
                                                @else
                                                    <td class="{{ $key1 . '_td' }} px-4 py-3 text-sm">
                                                        @if (!is_array($user[$value['column_name']]))
                                                            {{ $user[$value['column_name']] }}
                                                        @else
                                                            @php
                                                                json_encode($user[$value['column_name']]);
                                                            @endphp
                                                        @endif
                                                    </td>
                                                @endif
                                            @endif
                                        @endforeach
                                        @if (isset($data2))
                                            @foreach ($data2 as $key2 => $item)
                                                @if ($item['hidden'] === null)
                                                    @switch($item['control'])
                                                        @case('')
                                                            <td class="text-center">
                                                                <p
                                                                    class="rounded-md bg-yellow-400 px-2 py-0 text-xs font-semibold leading-tight text-red-400 dark:bg-red-500 dark:text-green-100">
                                                                    None Set
                                                                </p>
                                                            </td>
                                                        @break

                                                        @case('attachment')
                                                            <td class="text-center">
                                                                <x-render.attachment attachment="{{ $user->id }}"
                                                                    model="{{ $model }}"
                                                                    relationship="{{ $item['column_name'] }}" />
                                                            </td>
                                                        @break

                                                        @case('count')
                                                            <td class="text-center">
                                                                <x-render.count
                                                                    count="{{ $user->{$item['column_name']} ? $user->{$item['column_name']}->count() : '0' }}" />
                                                            </td>
                                                        @break

                                                        @case('column')
                                                            <td class="text-center">
                                                                @php
                                                                    $render = $user->{$item['column_name']}->{$item['control_param']} ?? '';
                                                                @endphp
                                                                {{ $render }}
                                                            </td>
                                                        @break

                                                        @default
                                                            <td class="px-4 py-3">
                                                                @if ($user->{$item['column_name']} != null)
                                                                    <x-render.user
                                                                        src="https://wp.tlcmodular.com/wp-content/uploads/2022/07/bfdc18a057769428cd67-150x150.jpg"
                                                                        name_rendered="{{ $user->{$item['column_name']}->name_rendered }}"
                                                                        email="{{ $user->{$item['column_name']}->email }}" />
                                                                @else
                                                                @endif
                                                            </td>
                                                    @endswitch
                                                @endif
                                            @endforeach
                                        @endif
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>
                </div>
                <div
                    class="grid border-t bg-gray-50 px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 sm:grid-cols-9">
                    <span class="col-span-3 flex items-center">
                        @if (isset($users) && count($users) > 0)
                            {{ $users->links('dashboards.pagination.showing') }}
                        @endif
                    </span>
                    <span class="col-span-2"></span>
                    <span class="col-span-4 mt-2 flex sm:mt-auto sm:justify-end">
                        <nav aria-label="Table navigation">
                            @if (isset($users) && count($users) > 0)
                                {{ $users->links('dashboards.pagination.template') }}
                            @endif
                        </nav>
                    </span>
                </div>
            </div>


        </div>
    </main>
@endsection
