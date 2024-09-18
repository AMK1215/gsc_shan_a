@extends('layouts.master')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Bank Account Lists</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Bank Account List</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card-header">
                        <a href="{{ route('admin.bank.create')}}" class="btn btn-primary">Create</a>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <table id="mytable" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Account Name</th>
                                    <th>Account Number</th>
                                    <th>Payment Type</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($banks as $bank)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$bank->account_name}}</td>
                                        <td>{{$bank->account_number}}</td>
                                        <td>{{$bank->paymentType->name}}</td>
                                        <td class="project-actions">
                                            <a href="{{ route('admin.bank.edit', $bank->id) }}" class="btn btn-info btn-sm"><i class="fas fa-edit"></i></a>
                                            <a class="btn btn-danger btn-sm" onclick="event.preventDefault(); document.getElementById('bank-form-{{ $bank->id }}').submit();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            <form id="bank-form-{{ $bank->id }}" action="{{ route('admin.bank.destroy', $bank->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach

                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
@endsection
