@extends('layouts.app')
@section('content')
<main class="h-full overflow-y-auto">
  <div class="container grid px-0 mx-auto">
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
            @foreach($data as $key => $value)
             @if (array_key_exists($key,$dataRender))
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
      </form>       --}}
      <div class="flex items-center justify-between p-4 my-4 text-base font-semibold text-purple-100 bg-purple-600 rounded-lg shadow-md focus:outline-none focus:shadow-outline-purple" 
      >
        <div class="flex items-center">
          <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
          </svg>
          <h3>{{ucfirst($type)}}</h3>
        </div>
        <span>View more →</span>
      </div>
      <form action="{{route($type.'_renderprop.index')}}" method="GET">
        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
          <div>
            <input type="text" name="search" 
            class="px-3 py-2 bg-white border shadow-sm border-slate-300 placeholder-slate-400 focus:outline-none focus:border-sky-500 focus:ring-sky-500 block w-full rounded-md sm:text-sm focus:ring-1" 
            value="{{$search}}">
          </div>
          <div>
            <button type="submit" 
            class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-emerald-500 border border-transparent rounded-lg active:bg-emerald-600 hover:bg-emerald-200 focus:outline-none focus:shadow-outline-purple"
            >Search</button>
          </div>
        </div>
      </form> 
      <div class="w-full mt-2 mb-8 overflow-hidden rounded-lg shadow-sm border">
        <div class="w-full overflow-x-auto">     
          <table class="w-full whitespace-no-wrap">
            <thead>
            <tr
              class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800"
            >
            <th class="px-4 py-3">Action</th>
            @foreach($data as $key => $value)
              <th class="px-4 py-3 {{$key.'_th'}}">{{$value['column_name']}}</th>
            @endforeach
            @foreach($data2 as $key => $value)
              <th class="px-4 py-3 {{$key.'_th'}}">{{$value['column_name']}}</th>
            @endforeach
            </tr>
            </thead>
            <tbody  class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
              @if (isset($data))
                @foreach($users as $key => $user)
                <tr class="text-gray-700 dark:text-gray-400">
                    <td class="px-4 py-3 text-sm text-center">
                        <button class="px-2 py-2 text-sm font-medium leading-5 text-red-600 rounded-lg dark:text-red-400 focus:outline-none focus:shadow-outline-gray btn-delete-user" data-url="{{ route($type.'_manage.destroy',$user->id) }}"​ type="button"><i class="fas fa-trash"></i></button>
                    </td>
                    @foreach ($data as $key1 => $value)
                        @if($value['column_name'] === 'id')
                        {{-- {{dd($type.'_edit.update')}} --}}
                        <td class="px-4 py-3 text-sm">
                          <a href="{{route($type.'_edit.update',$user[$value['column_name']])}}">{{$user[$value['column_name']]}}</a>
                        </td>
                        @else
                        <td class="{{$key1.'_td'}} px-4 py-3 text-sm">
                          @if (!is_array($user[$value['column_name']]))
                            {{$user[$value['column_name']]}}
                          @else
                            @php
                              json_encode($user[$value['column_name']]) 
                            @endphp
                          @endif
                        </td>
                        @endif
                    @endforeach
                    @if (isset($data2))
                        @foreach ($data2 as $key2 => $item)
                          @if ($item['control'] === 'attachment')
                          <td class="text-center">
                            <x-render.attachment attachment="{{$user->id}}" model={{$model}} relationship="{{$item['column_name']}}"/>
                          </td>
                          @elseif ($item['control'] === 'count')
                            <td class="text-center">
                              <x-render.count count="{{$user->{$item['column_name']}->count()}}"/>
                            </td> 
                          @else
                          <td class="px-4 py-3">
                              @if ($user->{$item['column_name']} != null)
                                <x-render.user />
                              @else
                                  
                              @endif
                            
                            </td>
                          @endif
                        @endforeach
                    @endif
                </tr>
                @endforeach
              @endif
            </tbody>
            <tfoot >
            </tfoot>
          </table> 
        </div>
        <div
        class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800"
        >
        <span class="flex items-center col-span-3">
          Showing 21-30 of 100
        </span>
        <span class="col-span-2"></span>
        <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
          <nav aria-label="Table navigation">
            @if(isset($users) && count($users) > 0)
              {{$users->links('dashboards.pagination.template')}}
            @endif
          </nav> 
        </span>
      </div> 
      </div>
          
    
  </div>
</main>       
@endsection