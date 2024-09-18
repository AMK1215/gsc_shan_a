@extends('layouts.master')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Deposit</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Deposit</li>
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
                   <a href="{{ route('admin.agent.index') }}" class="btn btn-success">
                       <i class="fas fa-arrow-left" style="font-size: 20px;"></i> Back
                   </a>
            </span>
                    </h3>
                </div>
                <form action="{{route('admin.agent.makeDeposit', $agent->id)}}" method="POST">
                    @csrf
                    <div class="card-body mt-2">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>AgentId<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="" value="{{$agent->user_name}}" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Name<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="" value="{{$agent->name}}" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Current Balance<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="" value="{{number_format($agent->wallet->balance, 2)}}" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Amount<span class="text-danger">*</span></label>
                                    <span class="badge badge-success">Max:{{ number_format(optional(auth()->user()->wallet)->balance, 2) }}</span>
                                    <input type="text" class="form-control" name="amount">
                                    @error('amount')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Note</label>
                                    <textarea name="note" id="" cols="30" rows="5" class="form-control"></textarea>
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
