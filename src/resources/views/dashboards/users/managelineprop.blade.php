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
        <form action="{{route('manageprop.store')}}" method="POST">
          @csrf
            <table id="table_run" class="table table-bordered table-striped text-center"  >
                <thead>
                <tr>
                <th>No.</th>
                <th>Table Name</th>
                <th>Name</th>
                <th>Column Name</th>
                <th>Column Type</th>
                <th>Label</th>
                <th>Control</th>
                <th>Col span</th>
                <th>New Line </th>
                <th>Action</th>
                </tr>
                </thead>
                <tbody  id="tbody-run-live">
                  @if (isset($names))
                        @foreach($names as $key => $name)
                          <tr >
                              <td >{{$key + 1}}</td>
                              <td >
                                <input type="text" class="form-control" name="table_name[]" value="{{$columnTableNames[$key]}}" readonly>
                              </td>
                              <td >
                                  <input type="text" class="form-control" name="name[]" value="{{$name}}" readonly>
                              </td>
                              
                              <td>
                                  <input type="text" class="form-control" name="column_name[]" value="{{$columnNames[$key]}}" readonly>
                              </td>
                              <td  >
                                  <input type="text" class="form-control" name="column_type[]" value="{{$columnTypes[$key]}}" readonly>
                              </td>
                              <td  >
                                <input type="text" class="form-control" name="label[]" value="{{$columnLabels[$key]}}">
                              </td>
                              
                              <td >
                                <select name="control[]" class="form-control">
                                    <option value="input" 
                                    @if($columnControls[$key] == 'input') 
                                    selected
                                    @endif>Input</option>
                                    <option value="checkbox"
                                    @if ($columnControls[$key] == 'checkbox') 
                                    selected
                                    @endif>Checkbox</option>
                                    <option value="radio" 
                                    @if ($columnControls[$key] == 'radio') 
                                    selected
                                    @endif>Radio</option>
                                    <option value="date"
                                    @if ($columnControls[$key] == 'date') 
                                    selected
                                    @endif>Date</option>
                                    <option value="table"
                                    @if ($columnControls[$key] == 'table') 
                                    selected
                                    @endif>Table</option>
                                  </select>
                              </td>
                              <td >
                                <input type="text" class="form-control" name="col_span[]" value="{{$columnColSpans[$key]}}">
                              </td>
                              <td >
                              <select name="new_line[]" class="form-control">
                                <option value="false"
                                @if($columnWrapModes[$key] == 'false') 
                                    selected
                                @endif>False</option>
                                <option value="true"
                                @if($columnWrapModes[$key] == 'true') 
                                    selected
                                @endif>True</option>
                              </select>
                              </td>
                              <td class="text-center" style="width: 50px;
                              vertical-align: middle;">
                              <button class="btn btn-danger"><i class="fas fa-trash"></i></button>
                              </td>
                          </tr> 
                        @endforeach
                  @else
                  @foreach($columnNames as $key => $columnName)
                        <tr >
                            <td >{{$key + 1}}</td>
                            <td ><input type="text" name="table_name[]" class="form-control" value="{{$columnTableNames[$key]}}" readonly></td>

                            <td >
                                <input type="text" name="name[]" class="form-control" value="_{{$columnName}}" readonly>
                            </td>
                            
                            <td style="width: 100px;
                                vertical-align: middle;">
                                <input type="text" name="column_name[]" class="form-control" value="{{$columnName}}" readonly>
                            </td>
                            <td  >
                                <input type="text" name="column_type[]" class="form-control" value="{{$columnTypes[$key]}}" readonly>
                            </td>
                            <td  >
                               <input type="text" name="label[]" class="form-control">
                            </td>
                            
                            <td >
                              <select name="control[]" class="form-control">
                                  <option value="input">Input</option>
                                  <option value="checkbox">Checkbox</option>
                                  <option value="radio">Radio</option>
                                  <option value="date">Date</option>
                                  <option value="table">Table</option>
                                </select>
                            </td>
                            <td >
                              <input type="text" name="col_span[]" class="form-control">
                            </td>
                            <td>
                             <select name="new_line[]" class="form-control">
                               <option value="false">False</option>
                              <option value="true">True</option>
                            </select>
                            </td>
                            <td class="text-center" style="width: 50px;
                            vertical-align: middle;">
                            <button class="btn btn-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr> 
                @endforeach
                  @endif
                    
                </tbody>
                <tfoot >
                    {{-- <tr >
                        <th>Create prop</th>
                        <th>
                            <input type="text" class="form-control">
                        </th>
                        <th>
                            <button class="btn btn-success">Create <i class="fas fa-plus"></i></button>
                        </th>
                    </tr> --}}
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