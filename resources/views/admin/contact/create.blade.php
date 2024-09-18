@extends('layouts.master')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Contact</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active">create contact</li>
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
                <form action="{{route('admin.contact.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body mt-2">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                <label>Types<span class="text-danger">*</span></label>
                                <select class="form-control select2bs4" style="width: 100%;" name="media_type_id">
                                    @foreach($mediaTypes as $type)
                                        <option value="{{$type->id}}">{{$type->name}}</option>
                                    @endforeach
                                </select>

                                @error('media_type_id')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Account<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="account" value="{{old('title')}}">
                                    @error('account')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>

        </div>
        </div>
    </section>
@endsection
