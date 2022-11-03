@extends('layouts.applayout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <button type="button" class="btn btn-primary btn-uploadfiles" data-target="#edit" data-toggle="modal"><i class="fas fa-cloud-upload-alt"></i></button>
                </div>
                <div class="card-body">
                    <table class="table-striped table">
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
                                <td>{{ $i++ }}</td>
                                <td>
                                    @php
                                    $url_thumbnail = $path . $file->url_thumbnail;
                                    $url_media = $path . $file->url_media;
                                    @endphp
                                    <a href="{{ $url_media }}">
                                        <img src="{{ $url_thumbnail }}" alt="{{ $file->filename }}" style="width: 150px; height: 150px;">
                                    </a>
                                </td>
                                <td>{{ $file->filename }}</td>
                                <td>{{ $file->url_media }}</td>
                                {{-- <td>{{ $file->user->name }}</td> --}}
                                <td>
                                    <div class="btn-group">
                                        <button data-url="{{ route('upload_add.destroy', $file->id) }}" ​ class="btn btn-danger btn-delete"><i class="fas fa-trash">
                                            </i></button>
                                        <form action="{{ route('upload_add.download', $file->id) }}" method="GET">
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
{{-- <main class="h-full overflow-y-auto">
    <div class="container grid px-0 mx-auto">
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
<div class="card-header">
    <button type="button" class="btn btn-primary btn-uploadfiles" data-target="#edit" data-toggle="modal"><i class="fas fa-cloud-upload-alt"></i></button>
</div>
<div class="w-full mt-2 mb-8 overflow-hidden rounded-lg shadow-sm border">
    <div class="w-full overflow-x-auto">
        <table class="w-full whitespace-no-wrap">
            <thead>
                <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                    <th class="px-4 py-3 text-center">#</th>
                    <th class="px-4 py-3 text-center">Image</th>
                    <th class="px-4 py-3 text-center">Files Name</th>
                    <th class="px-4 py-3 text-center">URL</th>
                    <th class="px-4 py-3 text-center">User Upload File</th>
                    <th class="px-4 py-3 text-center">Edit</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                @php $i=1; @endphp
                @forelse ($files as $file)
                <tr class="text-gray-700 dark:text-gray-400">
                    <td class="px-4 py-3 text-center text-sm">{{$i++;}}</td>
                    <td class="px-4 py-3 text-center text-sm">
                        @php
                        $url_thumbnail = $path.$file->url_thumbnail;
                        $url_media = $path.$file->url_media;
                        @endphp
                        <a href="{{$url_media}}">
                            <img src="{{$url_thumbnail}}" alt="{{$file->filename}}" style="width: 150px; height: 150px;">
                        </a>
                    </td>
                    <td class="px-4 py-3 text-center text-sm">{{$file->filename}}</td>
                    <td class="px-4 py-3 text-center text-sm">{{$file->url_media}}</td>
                    <td class="px-4 py-3 text-center text-sm">{{$file->user->name}}</td>
                    <td class="px-4 py-3 text-center text-sm">
                        <div class="">
                            <button data-url="{{route('upload_add.destroy',$file->id)}}" ​ class="px-2 py-2 text-sm font-medium leading-5 text-red-600 rounded-lg dark:text-red-400 focus:outline-none focus:shadow-outline-gray btn-delete"><i class="fas fa-trash">
                                </i></button>
                            <form action="{{route('upload_add.download',$file->id)}}" method="GET">
                                <button type="submit" class="px-2 py-2 text-sm font-medium leading-5 text-red-600 rounded-lg dark:text-red-400 focus:outline-none focus:shadow-outline-gray"><i class="fas fa-download"></i></button>
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
    <div class="grid px-4 py-3 text-center text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
        <span class="flex items-center col-span-3">
            Showing 21-30 of 100
        </span>
        <span class="col-span-2"></span>
        <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
            <nav aria-label="Table navigation">
                @if (isset($users) && count($users) > 0)
                {{$users->links('dashboards.pagination.template')}}
                @endif
            </nav>
        </span>
    </div>
</div>
</div>
</main> --}}
