@extends('layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Categorybanner List</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Categorybanner list</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Category Banner List</h3>
                            <a href="{{ route('categorybanner.create') }}" class="btn btn-primary">Add Banner</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>                                  
                                    <th>Title</th>  
                                    <th>Category</th>
                                    <th>Link</th> 
                                    <th>Image</th>
                                    <th style="width: 40px">Action</th>
                               
                                </tr>
                            </thead>
                            <tbody>
                                @if($categorybanners->count())
                                @foreach ($categorybanners as $categorybanner)
                                    <tr>
                                    <td>{{ $categorybanner->id }}</td>
                                    <td>{{$categorybanner->title}}</td>
                                    @if($categorybanner->category)
                                    <td>{{$categorybanner->category->name}}</td>
                                    @else
                                    <td>None</td>
                                    @endif
                                    <td>{{$categorybanner->link}}</td>
                                    <td>
                                        <div style="max-width: 70px; max-height:70px;overflow:hidden">
                                            <img src="{{ asset($categorybanner->image) }}" class="img-fluid img-rounded" alt="">
                                         </div>
                                        </td>
                    
                                        <td class="d-flex">
                                            <a href="{{ route('categorybanner.show', [$categorybanner->id]) }}" class="btn btn-sm btn-success mr-1"> <i class="fas fa-eye"></i> </a>
                                            <a href="{{ route('categorybanner.edit', [$categorybanner->id]) }}" class="btn btn-sm btn-primary mr-1"> <i class="fas fa-edit"></i> </a>
                                            <form action="{{ route('categorybanner.destroy', [$categorybanner->id]) }}" class="mr-1" method="POST">
                                                @method('DELETE')
                                                @csrf 
                                                <button type="submit" class="btn btn-sm btn-danger"> <i class="fas fa-trash"></i> </button>
                                            </form>
                                        </td>
                                       
                                    </tr>
                                @endforeach
                                @else   
                                    <tr>
                                        <td colspan="12">
                                            <h5 class="text-center">No categorybanners found.</h5>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                 
                </div>
            </div>
        </div>
    </div>
</div>
@endsection