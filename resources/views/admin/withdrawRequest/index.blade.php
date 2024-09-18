@extends('layouts.master')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Withdraw Request Lists</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Withdraw Request Lists</li>
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
                                    <th>Account Name</th>
                                    <th>Account No</th>
                                    <th>Current Balance</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($withdraws as $withdraw)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$withdraw->user->name}}</td>
                                        <td>{{$withdraw->paymentType->name}}</td>
                                        <td>{{$withdraw->account_name}}</td>
                                        <td>{{$withdraw->account_number}}</td>
                                        <td>{{$withdraw->user->wallet->balance}}</td>
                                        <td>{{$withdraw->amount }}</td>
                                        <td>
                                            @if($withdraw->status == 0)
                                                <span class="badge badge-warning text-white">Pending</span>
                                            @elseif($withdraw->status == 1)
                                                <span class="badge badge-success text-white">Approved</span>
                                            @else
                                                <span class="badge badge-danger text-white">Rejected</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <form action="{{route('admin.withdraw.approve', $withdraw->id)}}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="amount" value="{{ $withdraw->amount }}">
                                                    <input type="hidden" name="status" value="1">
                                                    <input type="hidden" name="player" value="{{ $withdraw->user_id }}">
                                                    @if($withdraw->status == 0)
                                                        <button class="btn btn-success p-1 me-1" type="submit">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    @endif
                                                </form>
                                                <form action="{{route('admin.withdraw.reject', $withdraw->id)}}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="status" value="2">
                                                    @if($withdraw->status == 0)
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
