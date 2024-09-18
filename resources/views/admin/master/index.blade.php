@extends('layouts.master')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Master Lists</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                    <li class="breadcrumb-item active">Master List</li>
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
                    <a href="{{ route('admin.master.create')}}" class="btn btn-primary">Create</a>
                </div>
                <div class="card">
                    <div class="card-body">
                        <table id="mytable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>MasterId</th>
                                    <th>Balance</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Agent Count</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$user->user_name}}</td>
                                    <td>{{ number_format(optional($user->wallet)->balance, 2) }}</td>
                                    <td>{{$user->name}}</td>
                                    <td>{{$user->phone}}</td>
                                    <td>{{$user->child($user->id)}}</td>
                                    <td>
                                        @if ($user->status == 2)
                                        <a onclick="event.preventDefault(); document.getElementById('banUser-{{ $user->id }}').submit();" class="me-2" href="#">
                                            <i class="fas fa-user-slash text-danger" style="font-size: 20px;"></i>
                                        </a>
                                        @elseif($user->status == 1)
                                        <a onclick="event.preventDefault(); document.getElementById('banUser-{{ $user->id }}').submit();" class="me-2" href="#">
                                            <i class="fas fa-user-check text-success" style="font-size: 20px;"></i>
                                        </a>
                                        @else
                                        <a href="" class="me-2" href="#">
                                            <i class="fas fa-user-check text-warning" style="font-size: 20px;"></i>
                                        </a>
                                        @endif
                                        <form class="d-none" id="banUser-{{ $user->id }}" action="{{ route('admin.master.ban', $user->id) }}" method="post">
                                            @csrf
                                        </form>
                                            <a href="{{route('admin.master.changePassword', $user->id)}}" class="btn btn-info btn-sm"><i class="fas fa-lock-open"></i></a>
                                            <a href="{{route('admin.master.edit', $user->id)}}" class="btn btn-info btn-sm">
                                            <i class="fas fa-edit" style="font-size: 20px;"></i>Edit
                                        </a>

                                         <a href="{{route('admin.logs', $user->id)}}" class="btn btn-success btn-sm">
                                            <i class="fas fa-sign-in-alt" style="font-size: 20px;"></i>Logs
                                        </a>
                                            <a href="{{route('admin.master.deposit', $user->id)}}"
                                               class="btn btn-warning btn-sm"><i class="fas fa-plus"></i>Dep</a>
                                            <a href="{{route('admin.master.withdraw', $user->id)}}"
                                               class="btn btn-primary btn-sm"><i class="fas fa-minus"></i>With</a>

                                    </td>
                                </tr>
                                @endforeach

                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <div class="modal fade" id="credentialsModal" tabindex="-1" role="dialog" aria-labelledby="credentialsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="credentialsModalLabel">Your Credentials</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Username:</strong> <span id="modal-username"></span></p>
                            <p><strong>Password:</strong> <span id="modal-password"></span></p>
                            <p><strong>Amount:</strong> <span id="modal-amount"></span></p>
                            <p><strong>URL:</strong> <span id="modal-url"></span></p>
                            <button class="btn btn-success" onclick="copyToClipboard()">Copy</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
    <script>
        var successMessage = @json(session('successMessage'));
        var userName = @json(session('user_name'));
        var password = @json(session('password'));
        var amount = @json(session('amount'));
        @if (session()->has('successMessage'))
        toastr.success(successMessage +
            `
        <div>
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#credentialsModal"
   data-username="${userName}"
   data-password="${password}"
   data-url="https://pandashan.online/login">View</button>
        </div>`, {
            allowHtml: true
        });
        @endif
        $('#credentialsModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var username = button.data('username');
            var password = button.data('password');
            var amount = button.data('amount');
            var url = button.data('url');

            $('#modal-username').text(username);
            $('#modal-password').text(password);
            $('#modal-amount').text(amount);
            $('#modal-url').text(url);
        });

        function copyToClipboard() {
            var username = document.getElementById('modal-username').innerText;
            var password = document.getElementById('modal-password').innerText;
            var amount = document.getElementById('modal-amount').innerText;
            var url = document.getElementById('modal-url').innerText;

            var textToCopy = "Username: " + username + "\nPassword: " + password + + "\nAmount: " + amount + "\nPassword: " + password + "\nURL: " + url;

            navigator.clipboard.writeText(textToCopy).then(function() {
                toastr.success("Credentials copied to clipboard!");
            }).catch(function(err) {
                toastr.error("Failed to copy text: " + err);
            });
        }
    </script>

@endsection
