@extends('layouts.master')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Log Lists</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                    <li class="breadcrumb-item active">Log List</li>
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
{{--                   <a href="{{ route('admin.master.index') }}" class="btn btn-success">--}}
{{--                       <i class="fas fa-arrow-left" style="font-size: 20px;"></i> Back--}}
{{--                   </a>--}}

                </div>
                <div class="card">
                    <div class="card-body">
                        <table id="mytable" class="table table-bordered table-hover">
                         <thead>
                          <th>#</th>
                          <th>User Id</th>
                          <th>IP Address</th>
                          <th>Login Time</th>
                        </thead>
                        <tbody>
                          @if(isset($logs))
                          @if(count($logs)>0)
                            @foreach ($logs as $log)
                            <tr>
                              <td>{{ $loop->iteration }}</td>
                              <td>
                                <span class="d-block">{{ $log->user->user_name }}</span>
                              </td>
                              <td class="text-sm  font-weight-bold">{{ $log->ip_address }}</td>
                              <td>{{ $log->created_at}}</td>
                            </tr>
                            @endforeach
                          @else
                          <tr>
                              <td col-span=8>
                                  There was no Players.
                              </td>
                          </tr>
                          @endif
                          @endif
                          {{-- kzt --}}

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
