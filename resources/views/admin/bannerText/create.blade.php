@extends('layouts.master')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create BannerText</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                    <li class="breadcrumb-item active">BannerText</li>
                </ol>

            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="col-lg-8 col-md-8 col-sm-10 col-12 offset-lg-2  offset-md-2  offset-sm-1 ">
                <div class="card mt-lg-4 mt-md-3 mt-sm-2 mt-2">

                    <div class="card-header">
                        <a href="{{ url('/admin/bannerText') }}" class="btn btn-dark ">
                            <i class="fas fa-arrow-left"  ></i> Back
                        </a>
                    </div>

                    <form action="{{ route('admin.bannerText.store') }}" method="post">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="">Text</label>
                                <input type="text" name="text" class="form-control" id="">
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="text-right">
                                <button type="submit" class="btn btn-dark">CreateBannerText</button>
                            </div>
                        </div>

                    </form>
                </div>

        </div>
    </div>
    </div>
</section>
@endsection
