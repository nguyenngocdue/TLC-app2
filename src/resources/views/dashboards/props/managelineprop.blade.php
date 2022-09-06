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
      <div class="card-body table-responsive">
        <form action="{{route($type.'_managelineprop.store')}}" method="POST">
          @csrf
            <table id="table_manage" class="table table-bordered table-striped text-center">
                <thead>
                <tr>
                <th>No.</th>
                <th>Name</th>
                <th>Table Name</th>
                <th>Column Name</th>
                <th>Column Type</th>
                <th>Label</th>
                <th>Control</th>
                <th>Col span</th>
                <th>New Line</th>
                <th>Action</th>
                </tr>
                </thead>
                <tbody  id="tbody-run-live">
                  @if (isset($names))
                  @php
                      $number = 1;
                      $controls = ['attachment', 'checkbox', 'datetime', 'dropdown', 'number', 'picker_time', 'picker_date', 'picker_month', 'picker_week', 'picker_quater', 'picker_year', 'radio', 'switch', 'text', 'textarea'];
                  @endphp
                        @foreach($names as $key => $name)
                          <tr class="table-line-{{$colorLines[$key]}}">
                              <td >{{$number}}</td>
                              @php
                                $number++;
                              @endphp
                              <td >
                                {{$name}}
                                <input type="text" class="form-control" name="name[]" value="{{$name}}" readonly hidden>
                              </td>
                              <td >
                                {{$columnTableNames[$key]}}
                                <input type="text" class="form-control" name="table_name[]" value="{{$columnTableNames[$key]}}" readonly hidden>
                              </td>
                              <td>
                                {{$columnNames[$key]}}
                                  <input type="text" class="form-control" name="column_name[]" value="{{$columnNames[$key]}}" readonly hidden>
                              </td>
                              <td  >
                                  <input type="text" class="form-control" name="column_type[]" value="{{$columnTypes[$key]}}" readonly>
                              </td>
                              <td  >
                                <input type="text" class="form-control" name="label[]" value="{{$columnLabels[$key]}}">
                              </td>
                              <td class="text-center">
                                <select name="control[]" class="form-control text-center">
                                  @foreach($controls as $control)
                                    @if($columnControls[$key] === $control)
                                    <option value="{{$control}}" selected >{{ucfirst($control)}}</option>
                                    @else
                                      <option value="{{$control}}">{{ucfirst($control)}}</option>
                                    @endif
                                  @endforeach
                                  </select>
                              </td>
                              <td  style="width: 80px;">
                                <input  type="text" class="form-control text-center" name="col_span[]" value="{{$columnColSpans[$key]}}">
                              </td>
                              <td >
                              <select name="new_line[]" class="form-control text-center">
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
                              <td >
                              @if ($colorLines[$key] == 'removed')
                              <button class="btn btn-danger btn-delete" data-url="{{ route($type.'_managelineprop.destroy',$name) }}"​ type="button"><i class="fas fa-trash"></i></button>
                              @endif
                              </td>
                          </tr> 
                        @endforeach
                  @else
                  @foreach($columnNames as $key => $columnName)
                        <tr class="table-line-new">
                            <td >{{$key + 1}}</td>
                            <td >
                              <input type="text" name="name[]" class="form-control" value="{{$columnTableNames[$key]}}|{{$columnName}}" readonly>
                            </td>
                            <td>
                              <input type="text" name="table_name[]" class="form-control" value="{{$columnTableNames[$key]}}" readonly>
                            </td>
                            <td style="width: 100px;
                                vertical-align: middle;">
                                <input type="text" name="column_name[]" class="form-control" value="{{$columnName}}" readonly>
                            </td>
                            <td  >
                                <input type="text" name="column_type[]" class="form-control" value="{{$columnTypes[$key]}}" readonly>
                            </td>
                            <td  >
                               <input type="text" name="label[]" class="form-control" value="{{$columnName}}">
                            </td>
                            
                            <td >
                              <select name="control[]" class="form-control">
                                @foreach($controls as $control)
                                  <option value="{{$control}}">{{ucfirst($control)}}</option>
                                @endforeach
                              </select>
                            </td>
                            <td >
                              <input type="text" name="col_span[]" class="form-control" value="12">
                            </td>
                            <td>
                             <select name="new_line[]" class="form-control">
                               <option value="false">False</option>
                              <option value="true">True</option>
                            </select>
                            </td>
                            <td >
                              @if (isset($colorLines))
                                <button class="btn btn-danger btn-delete" data-url="{{ route($type.'_manageprop.destroy',$name) }}"​ type="button"><i class="fas fa-trash"></i></button>
                              @endif
                            </td>
                        </tr> 
                @endforeach
                  @endif
                    
                </tbody>
                <tfoot >
                </tfoot>
            </table>
            <button class="btn btn-primary" type="submit">Update</button>
        </form>
        
      </div>
      <!-- /.card-body -->
    </div>
  </div>
</div>
@endsection