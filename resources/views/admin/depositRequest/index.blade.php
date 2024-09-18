@extends('layouts.master')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Deposit Request Lists</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Deposit Request Lists</li>
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
                    <div class="card">
                        <div class="card-body">
                            <table id="mytable" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>PlayerName</th>
                                    <th>PaymentType</th>
                                    <th>ReferenceNo</th>
                                    <th>Account Name</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($deposits as $deposit)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$deposit->user->name}}</td>
                                        <td>{{$deposit->bank->paymentType->name}}</td>
                                        <td>{{$deposit->reference_number}}</td>
                                        <td>{{$deposit->bank->account_name}}</td>
                                        <td>{{ $deposit->amount }}</td>
                                        <td>
                                            @if($deposit->status == 0)
                                                <span class="badge badge-warning text-white">Pending</span>
                                            @elseif($deposit->status == 1)
                                                <span class="badge badge-success text-white">Approved</span>
                                            @else
                                                <span class="badge badge-danger text-white">Rejected</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <form action="{{route('admin.deposit.approve', $deposit->id)}}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="amount" value="{{ $deposit->amount }}">
                                                    <input type="hidden" name="status" value="1">
                                                    <input type="hidden" name="player" value="{{ $deposit->user_id }}">
                                                    @if($deposit->status == 0)
                                                        <button class="btn btn-success p-1 me-1" type="submit">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    @endif
                                                </form>
                                                <form action="{{route('admin.deposit.reject', $deposit->id)}}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="status" value="2">
                                                    @if($deposit->status == 0)
                                                        <button class="btn btn-danger p-1 me-1 ml-2" type="submit">
                                                            <i class="fas fa-times" style="font-size: 20px"></i>
                                                        </button>
                                                    @endif
                                                </form>
                                            </div>
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
