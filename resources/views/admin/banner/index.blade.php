@extends('layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Banner List</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Banner list</li>
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
                            <h3 class="card-title">Banner List</h3>
                            <a href="{{ route('banner.create') }}" class="btn btn-primary">Add Banner</a>
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
                                @if($banners->count())
                                @foreach ($banners as $banner)
                                    <tr>
                                    <td>{{ $banner->id }}</td>
                                    <td>{{$banner->title}}</td>
                                    @if($banner->category)
                                    <td>{{$banner->category->name}}</td>
                                    @else
                                    <td>None</td>
                                    @endif
                                    <td>{{$banner->link}}</td>
                                    <td>
                                        <div style="max-width: 70px; max-height:70px;overflow:hidden">
                                            <img src="{{ asset($banner->image) }}" class="img-fluid img-rounded" alt="">
                                         </div>
                                        </td>
                    
                                        <td class="d-flex">
                                            <a href="{{ route('banner.show', [$banner->id]) }}" class="btn btn-sm btn-success mr-1"> <i class="fas fa-eye"></i> </a>
                                            <a href="{{ route('banner.edit', [$banner->id]) }}" class="btn btn-sm btn-primary mr-1"> <i class="fas fa-edit"></i> </a>
                                            <form action="{{ route('banner.destroy', [$banner->id]) }}" class="mr-1" method="POST">
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
                                            <h5 class="text-center">No banners found.</h5>
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