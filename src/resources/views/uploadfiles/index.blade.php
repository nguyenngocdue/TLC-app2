@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col">
            <div class="card">
                <div class="card-header" >
                    <button type="button" class="btn btn-primary btn-uploadfiles" data-target="#edit" data-toggle="modal"><i class="fas fa-cloud-upload-alt" ></i></button>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Files Name</th>
                                <th>URL</th>
                                <th>User Upload File</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i=1; @endphp
                            @forelse ($files as $file)
                                <tr>
                                    <td>{{$i++;}}</td>
                                    <td>
                                        @php
                                            $url_thumbnail = $path.$file->url_thumbnail;
                                            $url_media = $path.$file->url_media;
                                        @endphp
                                        <a href="{{$url_media}}">
                                            <img src="{{$url_thumbnail}}" alt="{{$file->filename}}" style="width: 150px; height: 150px;">
                                        </a>
                                    </td>
                                    <td>{{$file->filename}}</td>
                                    <td>{{$file->url_media}}</td>
                                    <td>{{$file->user->name}}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button data-url="{{route('uploadfiles.destroy',$file->id)}}"​ class="btn btn-danger btn-delete"><i class="fas fa-trash">
                                            </i></button>
                                            <form action="{{route('uploadfiles.download',$file->id)}}" method="GET" >
                                                <button type="submit" class="btn btn-warning"><i class="fas fa-download"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No products yet!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('uploadfiles.uploadfile')
@endsection