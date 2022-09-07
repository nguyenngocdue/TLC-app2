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
      <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
        avatars
      </h4>
      <form action="{{route($type.'_renderprop.index')}}" method="GET" class="ml-2 mb-2">
        <div class="row">
          <input type="text" name="search" 
          class="mt-1 px-3 py-2 bg-white border shadow-sm border-slate-300 placeholder-slate-400 focus:outline-none focus:border-sky-500 focus:ring-sky-500 block w-full rounded-md sm:text-sm focus:ring-1" 
          value="{{$search}}">
          <button type="submit" 
          class="mt-2 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-emerald-500 border border-transparent rounded-lg active:bg-emerald-600 hover:bg-emerald-200 focus:outline-none focus:shadow-outline-purple"
          >Search</button>
        </div>
      </form> 
      <div class="w-full mb-8 overflow-hidden rounded-lg shadow-xs">
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
            </tr>
            </thead>
            <tbody  class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
              @if (isset($data))
                @foreach($users as $key => $user)
                <tr class="text-gray-700 dark:text-gray-400">
                    <td class="px-4 py-3 text-sm">
                        <button class="btn btn-danger btn-delete-user" data-url="{{ route($type.'_manage.destroy',$user->id) }}"​ type="button"><i class="fas fa-trash"></i></button>
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
                </tr>
                @endforeach
              @endif
                
            </tbody>
            <tfoot >
            </tfoot>
          </table> 
        </div>
        <nav aria-label="Table navigation">
          @if(isset($users) && count($users) > 0)
            {{$users->links('dashboards.pagination.template')}}
          @endif
        </nav> 
      </div>     
    
  </div>
</main>       
@endsection