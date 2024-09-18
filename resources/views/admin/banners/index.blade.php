@extends('layouts.master')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Banner</h1>

            </div>
            <div class="col-sm-6">
                <div class="float-sm-right">
                    <a href="{{route('admin.banners.create')}}" class="btn btn-dark">Create</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Image</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($banners as $key => $banner)
                            <tr data-widget="expandable-table" aria-expanded="false">
                                <td class="text-sm font-weight-normal">{{ ++$key }}</td>
                                <td>
                                    <img width="100px" class="img-thumbnail" src="{{ $banner->img_url }}" alt="">
                                </td>
                                <td class="text-sm font-weight-normal">{{ $banner->created_at->format('F j, Y') }}</td>
                                <td class="project-actions">
                                    <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn btn-info btn-sm"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn btn-danger btn-sm" onclick="event.preventDefault();
                                                     document.getElementById('banner-form').submit();"><i class="fas fa-trash"></i></a>
                                    <form id="banner-form" action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
