@extends('layouts.app')

@section('content')
<main class="h-full overflow-y-auto">
  <div class="container grid px-0 mx-auto">
    <div class="flex items-center justify-between p-4 my-4 text-base font-semibold text-purple-100 bg-purple-600 rounded-lg shadow-md focus:outline-none focus:shadow-outline-purple" 
    >
      <div class="flex items-center">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
        </svg>
        <h3>{{ucfirst($type)}}</h3>
      </div>
      <span>Relationship →</span>
    </div>
      <form action="{{route($type.'_managerelationship.store')}}" method="POST">
        @csrf
        <div class="w-full mt-2 mb-8 overflow-hidden rounded-lg shadow-sm border">
          <div class="w-full overflow-x-auto">
            <table id="table_manage" class="w-full whitespace-no-wrap">
                <thead>
                <tr
                  class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800"
                >
                <th class="px-4 py-3">No.</th>
                <th class="px-4 py-3">Name</th>
                <th class="px-4 py-3">Column Name</th>
                <th class="px-4 py-3">Eloquent</th>
                <th class="px-4 py-3">Param_1</th>
                <th class="px-4 py-3">Param_2</th>
                <th class="px-4 py-3">Param_3</th>
                <th class="px-4 py-3">Param_4</th>
                <th class="px-4 py-3">Param_5</th>
                <th class="px-4 py-3">Param_6</th>
                <th class="px-4 py-3">Label</th>
                <th class="px-4 py-3">Control</th>
                <th class="px-4 py-3">Col span</th>
                <th class="px-4 py-3">New Line</th>
                <th class="px-4 py-3">Action</th>
                </tr>
                </thead>
                <tbody  class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
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
                                {{$columnEloquents[$key]}}
                                <input type="text" name="eloquent[]" 
                                value="{{$columnEloquents[$key]}}" readonly hidden>
                              </td>
                              <td class="px-4 py-3 text-sm">
                                {{$columnParam1s[$key]}}
                                <input type="text" name="param_1[]" 
                                value="{{$columnParam1s[$key]}}" readonly hidden>
                              </td>
                              <td class="px-4 py-3 text-sm">
                                {{$columnParam2s[$key]}}
                                <input type="text" name="param_2[]" 
                                value="{{$columnParam2s[$key]}}" readonly hidden>
                              </td>
                              <td class="px-4 py-3 text-sm">
                                {{$columnParam3s[$key]}}
                                <input type="text" name="param_3[]" 
                                value="{{$columnParam3s[$key]}}" readonly hidden>
                              </td>
                              <td class="px-4 py-3 text-sm">
                                {{$columnParam4s[$key]}}
                                <input type="text" name="param_4[]" 
                                value="{{$columnParam4s[$key]}}" readonly hidden>
                              </td>
                              <td class="px-4 py-3 text-sm">
                                {{$columnParam5s[$key]}}
                                <input type="text" name="param_5[]" 
                                value="{{$columnParam5s[$key]}}" readonly hidden>
                              </td>
                              <td class="px-4 py-3 text-sm">
                                {{$columnParam6s[$key]}}
                                <input type="text" name="param_6[]" 
                                value="{{$columnParam6s[$key]}}" readonly hidden>
                              </td>
                              <td class="px-4 py-3 text-sm">
                                <input type="text" 
                                class="mt-1 px-3 py-2 bg-white border shadow-sm border-slate-300 placeholder-slate-400 focus:outline-none focus:border-sky-500 focus:ring-sky-500 block w-full rounded-md sm:text-sm focus:ring-1" 
                                name="label[]" value="{{ucwords(str_replace("_"," ",$columnLabels[$key]))}}">
                              </td>
                              <td class="px-4 py-3 text-sm">
                                <select name="control[]" 
                                class="text-center mt-1 px-3 py-2 w-max bg-white border shadow-sm border-slate-300 placeholder-slate-400 focus:outline-none focus:border-sky-500 focus:ring-sky-500 block w-full rounded-md sm:text-sm focus:ring-1"
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
                              <td  class="px-4 py-3 text-sm">
                                <input  type="text" 
                                class="text-center mt-1 px-3 py-2 max-w-fit bg-white border shadow-sm border-slate-300 placeholder-slate-400 focus:outline-none focus:border-sky-500 focus:ring-sky-500 block w-full rounded-md sm:text-sm focus:ring-1" 
                                name="col_span[]" value="{{$columnColSpans[$key]}}">
                              </td>
                              <td class="px-4 py-3 text-sm">
                                <select name="new_line[]" 
                                class="text-center mt-1 px-3 py-2 w-max bg-white border shadow-sm border-slate-300 placeholder-slate-400 focus:outline-none focus:border-sky-500 focus:ring-sky-500 block w-full rounded-md sm:text-sm focus:ring-1"
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
                              <td class="px-4 py-3 text-sm text-center">
                                @if ($colorLines[$key] == 'removed')
                                <button class="btn btn-danger btn-delete" data-url="{{ route($type.'_managerelationship.destroy',$name) }}"​ type="button"><i class="fas fa-trash"></i></button>
                                @endif
                              </td>
                          </tr> 
                        @endforeach
                  @else
                  @foreach($columnNames as $key => $columnName)
                        <tr class="table-line-new">
                            <td class="px-4 py-3 text-sm">{{$key + 1}}</td>
                            <td class="px-4 py-3 text-sm">
                              _{{$columnName}}
                                <input type="text" name="name[]" 
                                value="_{{$columnName}}" readonly hidden>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{$columnName}}
                                <input type="text" name="column_name[]" 
                                value="{{$columnName}}" readonly hidden>
                            </td>
                            <td class="px-4 py-3 text-sm">
                              {{isset($columnEloquentParams[$columnName][0]) ? $columnEloquentParams[$columnName][0] : ""}}
                              <input type="text" name="eloquent[]" 
                              value="{{isset($columnEloquentParams[$columnName][0]) ? $columnEloquentParams[$columnName][0] : ""}}" readonly hidden>
                            </td>
                            <td class="px-4 py-3 text-sm">
                              {{isset($columnEloquentParams[$columnName][1]) ? $columnEloquentParams[$columnName][1] : ""}}
                              <input type="text" name="param_1[]" 
                              value="{{isset($columnEloquentParams[$columnName][1]) ? $columnEloquentParams[$columnName][1] : ""}}" readonly hidden>
                            </td>
                            <td class="px-4 py-3 text-sm">
                              {{isset($columnEloquentParams[$columnName][2]) ? $columnEloquentParams[$columnName][2] : ""}}
                              <input type="text" name="param_2[]" 
                              value="{{isset($columnEloquentParams[$columnName][2]) ? $columnEloquentParams[$columnName][2] : ""}}" readonly hidden>
                            </td>
                            <td class="px-4 py-3 text-sm">
                              {{isset($columnEloquentParams[$columnName][3]) ? $columnEloquentParams[$columnName][3] : ""}}
                              <input type="text" name="param_3[]" 
                              value="{{isset($columnEloquentParams[$columnName][3]) ? $columnEloquentParams[$columnName][3] : ""}}" readonly hidden>
                            </td>
                            <td class="px-4 py-3 text-sm">
                              {{isset($columnEloquentParams[$columnName][4]) ? $columnEloquentParams[$columnName][4] : ""}}
                              <input type="text" name="param_4[]" 
                              value="{{isset($columnEloquentParams[$columnName][4]) ? $columnEloquentParams[$columnName][4] : ""}}" readonly hidden>
                            </td>
                            <td class="px-4 py-3 text-sm">
                              {{isset($columnEloquentParams[$columnName][5]) ? $columnEloquentParams[$columnName][5] : ""}}
                              <input type="text" name="param_5[]" 
                              value="{{isset($columnEloquentParams[$columnName][5]) ? $columnEloquentParams[$columnName][5] : ""}}" readonly hidden>
                            </td>
                            <td class="px-4 py-3 text-sm">
                              {{isset($columnEloquentParams[$columnName][6]) ? $columnEloquentParams[$columnName][6] : ""}}
                              <input type="text" name="param_6[]" 
                              value="{{isset($columnEloquentParams[$columnName][6]) ? $columnEloquentParams[$columnName][6] : ""}}" readonly hidden>
                            </td>
                            <td class="px-4 py-3 text-sm">
                              <input type="text" name="label[]"
                              class="mt-1 px-3 py-2 bg-white border shadow-sm border-slate-300 placeholder-slate-400 focus:outline-none focus:border-sky-500 focus:ring-sky-500 block w-full rounded-md sm:text-sm focus:ring-1" 
                              value="{{ucwords(str_replace("_"," ",$columnName))}}" >
                            </td>
                            <td class="px-4 py-3 text-sm">
                              <select name="control[]" 
                              class="text-center mt-1 px-3 py-2 w-max bg-white border shadow-sm border-slate-300 placeholder-slate-400 focus:outline-none focus:border-sky-500 focus:ring-sky-500 block w-full rounded-md sm:text-sm focus:ring-1">
                                @foreach($controls as $control)
                                <option value="{{$control}}">{{ucfirst($control)}}</option>
                                @endforeach
                              </select>
                            </td>
                            <td class="px-4 py-3 text-sm">
                              <input type="text" name="col_span[]" 
                              class="text-center mt-1 px-3 py-2 max-w-fit bg-white border shadow-sm border-slate-300 placeholder-slate-400 focus:outline-none focus:border-sky-500 focus:ring-sky-500 block w-full rounded-md sm:text-sm focus:ring-1" 
                              value="12">
                            </td>
                            <td class="px-4 py-3 text-sm">
                              <select name="new_line[]" 
                              class="text-center mt-1 px-3 py-2 w-max bg-white border shadow-sm border-slate-300 placeholder-slate-400 focus:outline-none focus:border-sky-500 focus:ring-sky-500 block w-full rounded-md sm:text-sm focus:ring-1">
                                <option value="false">False</option>
                                <option value="true">True</option>
                              </select>
                            </td>
                            <td class="px-4 py-3 text-sm text-center">
                              @if (isset($colorLines))
                                <button class="btn btn-danger btn-delete" data-url="{{ route($type.'_managerelationship.destroy',$name) }}"​ type="button"><i class="fas fa-trash"></i></button>
                              @endif
                            </td>
                        </tr> 
                @endforeach
                  @endif
                    
                </tbody>
              </table>
            </div>
            <button class="my-2 ml-2 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-emerald-500 border border-transparent rounded-lg active:bg-emerald-600 hover:bg-emerald-200 focus:outline-none focus:shadow-outline-purple" type="submit">Update</button>
        </div>  
      </form>  
        
  </div>
</main>
@endsection