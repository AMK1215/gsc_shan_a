@extends('layouts.master')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Banner</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                    <li class="breadcrumb-item active">Banner</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<section class="content">
    <div class="container-fluid">
        <div class="col-lg-8 col-md-8 col-sm-10 col-12 offset-lg-2  offset-md-2  offset-sm-1 ">
                <div class="card mt-lg-4 mt-md-3 mt-sm-2 mt-2">
                    <div class="card-header">
                        <a href="{{ url('/admin/banners') }}" class="btn btn-dark ">
                            <i class="fas fa-arrow-left"  ></i> Back
                        </a>
                    </div>
                    <form role="form" action="{{ route('admin.banners.update', $banner->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card-body mt-3">
                            <div class="form-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="" name="image">
                                    <label class="custom-file-label" for="customFile">Choose file</label>
                                </div>
                                <img src="{{ $banner->img_url }}" width="150px" class="img-thumbnail" alt="">
                            </div>

                        </div>
                        <div class="card-footer">
                            <div class="text-right">
                                <button type="submit" class="btn btn-dark ">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
        </div>
    </div>
</section>
@endsection
