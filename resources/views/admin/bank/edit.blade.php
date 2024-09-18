@extends('layouts.master')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Bank</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active">create Player</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                    <span>
                       <a href="{{ route('admin.bank.index') }}" class="btn btn-success">
                           <i class="fas fa-arrow-left" style="font-size: 20px;"></i> Back
                       </a>
                    </span>
                    </h3>
                </div>
                <form action="{{route('admin.bank.update', $bank->id)}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body mt-2">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Payment Types<span class="text-danger">*</span></label>
                                    <select class="form-control select2bs4" style="width: 100%;" name="payment_type_id">
                                        @foreach($paymentTypes as $paymentType)
                                            <option value="{{$paymentType->id}}"  {{$bank->payment_type_id == $paymentType->id ? 'selected' : ''}}>{{$paymentType->name}}</option>
                                        @endforeach
                                    </select>

                                    @error('payment_type_id')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Account Name<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="account_name" value="{{$bank->account_name}}">
                                    @error('account_name')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Account Number<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="account_number" value="{{$bank->account_number}}">
                                    @error('account_number')
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
    </section>
@endsection
