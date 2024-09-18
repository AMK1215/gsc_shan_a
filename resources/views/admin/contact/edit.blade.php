@extends('layouts.master')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Present</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active">create Present</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                    <span>
                       <a href="{{ route('admin.contact.index') }}" class="btn btn-success">
                           <i class="fas fa-arrow-left" style="font-size: 20px;"></i> Back
                       </a>
                    </span>
                    </h3>
                </div>
                <form action="{{route('admin.contact.update', $contact->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body mt-2">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Types<span class="text-danger">*</span></label>
                                    <input type="text" value="{{$contact->media_type->name}}" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Account</label>
                                    <input class="form-control" name="account" value="{{$contact->account}}">
                                    @error('account')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>

        </div>
        </div>
    </section>
@endsection
