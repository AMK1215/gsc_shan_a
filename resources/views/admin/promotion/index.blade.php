@extends('layouts.master')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Promotions</h1>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('admin.promotion.create')}}" class="btn btn-primary">Create</a>
                </div>
                <div class="card-body">
                    <table id="mytable" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Title</th>
                                <th>Image</th>
                                <th>Desc</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($promotions as $promotion)
                            <tr data-widget="expandable-table" aria-expanded="false">
                                <td class="text-sm font-weight-normal">{{ $loop->iteration }}</td>
                                <td>{{$promotion->title}}</td>
                                <td>
                                    <img width="150px" class="img-thumbnail" src="{{ $promotion->img_url }}" alt="">
                                </td>
                                <td class="text-sm font-weight-normal">{{ $promotion->description ?? '-'}}</td>
                                <td class="project-actions">
                                    <a href="{{ route('admin.promotion.edit', $promotion->id) }}" class="btn btn-info btn-sm"><i class="fas fa-edit"></i></a>
                                    <a class="btn btn-danger btn-sm" onclick="event.preventDefault(); document.getElementById('promotion-form-{{ $promotion->id }}').submit();">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <form id="promotion-form-{{ $promotion->id }}" action="{{ route('admin.promotion.destroy', $promotion->id) }}" method="POST" style="display: none;">
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
