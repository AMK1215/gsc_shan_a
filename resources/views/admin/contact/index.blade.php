@extends('layouts.master')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Contact</h1>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('admin.contact.create')}}" class="btn btn-primary">Create</a>
                </div>
                <div class="card-body">
                    <table id="mytable" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Type</th>
                                <th>Account</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($contacts as $contact)
                            <tr data-widget="expandable-table" aria-expanded="false">
                                <td class="text-sm font-weight-normal">{{ $loop->iteration }}</td>
                                <td>
                                    <img width="150px" class="img-thumbnail" src="{{ $contact->media_type->img_url }}" alt="">
                                </td>
                                <td class="text-sm font-weight-normal">{{ $contact->account}}</td>
                                <td class="project-actions">
                                    <a href="{{ route('admin.contact.edit', $contact->id) }}" class="btn btn-info btn-sm"><i class="fas fa-edit"></i></a>
                                    <a class="btn btn-danger btn-sm" onclick="event.preventDefault(); document.getElementById('contact-form-{{ $contact->id }}').submit();">
                                        <i class="fas fa-trash"></i>
                                    </a>

                                    <form id="contact-form-{{ $contact->id }}" action="{{ route('admin.contact.destroy', $contact->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
