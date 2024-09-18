@extends('layouts.master')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Live22 Win/Lose Report Detail</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Report Detail</li>
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
                        <a href="{{ route('admin.live22.wlreport') }}" class="btn btn-primary">Back</a>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <table id="mytable" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Game Name</th>
                                        <th>Bet Amount</th>
                                        <th>Valid Bet Amount</th>
                                        <th>Payout</th>
                                        <th>Win/Lose</th>
                                        <th>Result Type</th>
                                        <th>Tran Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($gameResults as $result)
                                        <tr>
                                            <td>{{ $result->game_name }}</td>
                                            <td>{{ number_format($result->bet_amount, 4) }}</td>
                                            <td>{{ number_format($result->valid_bet_amount, 4) }}</td>
                                            <td>{{ number_format($result->payout, 4) }}</td>
                                            <td>{{ number_format($result->win_lose, 4) }}</td>
                                            <td>{{ $result->result_type }}</td>
                                            <td>{{ $result->tran_date_time }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>

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
