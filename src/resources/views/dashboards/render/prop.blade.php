@extends('layouts.applayout')
@section('content')
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Dash Board</a></li>
            <li class="breadcrumb-item active">Manage Prop</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <div class="content">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Manage Prop:<h4 class="card-title" ></h4></h3>
        </div>
        <div class="card-body">
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
            </form>
        </div>
        <div class="card-header">
            <h3 class="card-title">Manage Prop:<h4 class="card-title" ></h4></h3>
        </div>
      <div class="card-body table-responsive">
            <form action="{{route($type.'_renderprop.index')}}" method="GET" class="ml-2 mb-2">
              <div class="row">
                <input type="text" name="search" class="form-control col-2" value="{{$search}}">
                <button type="submit" class="btn btn-success pd-2">Search</button>
              </div>
            </form>
            <table id="table_users" class="table table-bordered table-striped text-center">
                <thead>
                <tr>
                <th>Action</th>
                @foreach($data as $key => $value)
                  <th class="{{$key.'_th'}}">{{$value['column_name']}}</th>
                @endforeach
                </tr>
                </thead>
                <tbody  id="tbody-run-live">
                  @if (isset($data))
                    @foreach($users as $key => $user)
                    <tr>
                        <td>
                            <button class="btn btn-danger btn-delete-user" data-url="{{ route($type.'_manage.destroy',$user->id) }}"​ type="button"><i class="fas fa-trash"></i></button>
                        </td>
                        @foreach ($data as $key1 => $value)
                            @if($value['column_name'] === 'id')
                            <td><a href="{{route($type.'_edit.update',$user[$value['column_name']])}}">{{$user[$value['column_name']]}}</a></td>
                            @else
                            <td class="{{$key1.'_td'}}">
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
            <nav aria-label="Page navigation">
              @if(isset($users) && count($users) > 0)
                {{$users->links('dashboards.pagination.template')}}
              @endif
            </nav>
      </div>
      <!-- /.card-body -->
    </div>
  </div>
</div>
@endsection