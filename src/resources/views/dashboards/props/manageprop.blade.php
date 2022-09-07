@extends('layouts.app')

@section('content')
<main class="h-full overflow-y-auto">
  <div class="container grid px-0 mx-auto">
      <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
              avatars
      </h4>
        <form action="{{route($type.'_manageprop.store')}}" method="POST">
          @csrf
          <div class="w-full mb-8 overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
              <table class="w-full whitespace-no-wrap">
                  <thead>
                  <tr
                  class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800"
                  >
                  <th class="px-4 py-3">No.</th>
                  <th class="px-4 py-3">Name</th>
                  <th class="px-4 py-3">Column Name</th>
                  <th class="px-4 py-3">Column Type</th>
                  <th class="px-4 py-3">Label</th>
                  <th class="px-4 py-3">Control</th>
                  <th class="px-4 py-3">Col span</th>
                  <th class="px-4 py-3">New Line</th>
                  <th class="px-4 py-3">Action</th>
                  </tr>
                  </thead>
                  <tbody  class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800"
                  >
                    @php
                      $controls = json_decode(file_get_contents(storage_path() . "/json/configs/view/dashboard/props/controls.json"),true)['controls'];
                    @endphp
                    @if (isset($names))
                    @php
                        $number = 1;
                    @endphp
                          @foreach($names as $key => $name)
                            <tr class="text-gray-700 dark:text-gray-400 table-line-{{$colorLines[$key]}}">
                                <td class="px-4 py-3 text-sm">{{$number}}</td>
                                @php
                                  $number++;
                                @endphp
                                <td class="px-4 py-3 text-sm">
                                  {{$name}}
                                    <input type="text" 
                                    name="name[]" value="{{$name}}" readonly hidden>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                  {{$columnNames[$key]}}
                                    <input type="text" 
                                    name="column_name[]" value="{{$columnNames[$key]}}" readonly hidden>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                  {{$columnTypes[$key]}}
                                    <input type="text" 
                                    name="column_type[]" value="{{$columnTypes[$key]}}" readonly hidden>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                  <input type="text" 
                                  class="mt-1 px-3 py-2 bg-white border shadow-sm border-slate-300 placeholder-slate-400 focus:outline-none focus:border-sky-500 focus:ring-sky-500 block w-full rounded-md sm:text-sm focus:ring-1" 
                                  name="label[]" value="{{$columnLabels[$key]}}">
                                </td>
                                <td class="px-4 py-3 text-sm">
                                  <select name="control[]" 
                                  class="mt-1 px-3 py-2 bg-white border shadow-sm border-slate-300 placeholder-slate-400 focus:outline-none focus:border-sky-500 focus:ring-sky-500 block w-full rounded-md sm:text-sm focus:ring-1"
                                  >
                                      @foreach($controls as $control)
                                        @if($columnControls[$key] === $control)
                                        <option value="{{$control}}" selected >{{ucfirst($control)}}</option>
                                        @else
                                          <option value="{{$control}}">{{ucfirst($control)}}</option>
                                        @endif
                                      @endforeach
                                  </select>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                  <input  type="text" 
                                  class="mt-1 px-3 py-2 bg-white border shadow-sm border-slate-300 placeholder-slate-400 focus:outline-none focus:border-sky-500 focus:ring-sky-500 block w-full rounded-md sm:text-sm focus:ring-1" 
                                  name="col_span[]" value="{{$columnColSpans[$key]}}">
                                </td>
                                <td class="px-4 py-3 text-sm">
                                  <select name="new_line[]" 
                                  class="mt-1 px-3 py-2 bg-white border shadow-sm border-slate-300 placeholder-slate-400 focus:outline-none focus:border-sky-500 focus:ring-sky-500 block w-full rounded-md sm:text-sm focus:ring-1"
                                  >
                                    <option value="false"
                                    @if($columnNewLines[$key] == 'false') 
                                        selected
                                    @endif>False</option>
                                    <option value="true"
                                    @if($columnNewLines[$key] == 'true') 
                                        selected
                                    @endif>True</option>
                                  </select>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                @if ($colorLines[$key] == 'removed')
                                <button class="btn btn-danger btn-delete" data-url="{{ route($type.'_manageprop.destroy',$name) }}"​ type="button"><i class="fas fa-trash"></i></button>
                                @endif
                                </td>
                            </tr> 
                          @endforeach
                    @else
                    @foreach($columnNames as $key => $columnName)
                          <tr class="table-line-new text-gray-700 dark:text-gray-400">
                              <td class="px-4 py-3 text-sm">{{$key + 1}}</td>
                              <td class="px-4 py-3 text-sm">
                                _{{$columnName}}
                                  <input type="text" name="name[]" 
                                  class="form-control" 
                                  value="_{{$columnName}}" readonly hidden>
                              </td>
                              <td class="px-4 py-3 text-sm">
                                  {{$columnName}}
                                  <input type="text" name="column_name[]" 
                                  class="form-control" 
                                  value="{{$columnName}}" readonly hidden>
                              </td>
                              <td class="px-4 py-3 text-sm">
                                {{$columnTypes[$key]}}
                                  <input type="text" 
                                  name="column_type[]" 
                                  class="form-control" value="{{$columnTypes[$key]}}" readonly hidden>
                              </td>
                              <td class="px-4 py-3 text-sm">
                                <input type="text" name="label[]" 
                                class="mt-1 px-3 py-2 bg-white border shadow-sm border-slate-300 placeholder-slate-400 focus:outline-none focus:border-sky-500 focus:ring-sky-500 block w-full rounded-md sm:text-sm focus:ring-1" 
                                value="{{$columnName}}">
                              </td>
                              
                              <td class="px-4 py-3 text-sm">
                                <select name="control[]" 
                                class="mt-1 px-3 py-2 bg-white border shadow-sm border-slate-300 placeholder-slate-400 focus:outline-none focus:border-sky-500 focus:ring-sky-500 block w-full rounded-md sm:text-sm focus:ring-1"
                                >
                                  @foreach($controls as $control)
                                  <option value="{{$control}}">{{ucfirst($control)}}</option>
                                  @endforeach
                                </select>
                              </td>
                              <td class="px-4 py-3 text-sm">
                                <input type="text" name="col_span[]" 
                                class="mt-1 px-3 py-2 bg-white border shadow-sm border-slate-300 placeholder-slate-400 focus:outline-none focus:border-sky-500 focus:ring-sky-500 block w-full rounded-md sm:text-sm focus:ring-1" 
                                value="12">
                              </td>
                              <td class="px-4 py-3 text-sm">
                                <select name="new_line[]" 
                                class="mt-1 px-3 py-2 bg-white border shadow-sm border-slate-300 placeholder-slate-400 focus:outline-none focus:border-sky-500 focus:ring-sky-500 block w-full rounded-md sm:text-sm focus:ring-1"
                                >
                                  <option value="false">False</option>
                                  <option value="true">True</option>
                                </select>
                              </td>
                              <td class="px-4 py-3 text-sm">
                                @if (isset($colorLines))
                                  <button class="btn btn-danger btn-delete" data-url="{{ route('manageprop.destroy',$name) }}"​ type="button"><i class="fas fa-trash"></i></button>
                                @endif
                              </td>
                          </tr> 
                  @endforeach
                  @endif
                  </tbody>
                  <tfoot >
                  </tfoot>
              </table>
            </div>
          <button class="mt-2 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-emerald-500 border border-transparent rounded-lg active:bg-emerald-600 hover:bg-emerald-200 focus:outline-none focus:shadow-outline-purple" type="submit">Update</button>
          </div>
        </form>
  </div>
</main>
@endsection