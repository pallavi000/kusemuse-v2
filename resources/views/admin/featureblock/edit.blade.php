@extends('layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Edit Feature block</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('featureblock.index') }}">feature block list</a></li>
                    <li class="breadcrumb-item active">Edit feature</li>
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
                            <h3 class="card-title">Edit feature- {{ $feature->name }}</h3>
                            <a href="{{ route('featureblock.index') }}" class="btn btn-primary">Go Back to feature List</a>
                           

                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="row">
                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                            <div class="col-12 col-lg-6 offset-lg-3 col-md-8 offset-md-2">
                               
                                <form action="{{ route('featureblock.update', [$feature->id]) }}" method="POST" enctype="multipart/form-data">
                                   
                                    @csrf 
                                    @method('PUT')
                                    <div class="card-body">
                                  
                                        <div class="form-group">
                                            <label for="name">feature title</label>
                                            <input type="name" name="title" class="form-control" value="{{ $feature->title }}" placeholder="Enter name">
                                        </div>
                                        <div class="form-group">
                                            <label for="name">feature detail</label>
                                            <input type="detail" name="detail" class="form-control" value="{{ $feature->detail }}" placeholder="Enter detail">
                                        </div>
                                        <div class="form-group">
                                            <label for="name">feature Link</label>
                                            <input type="name" name="link" class="form-control" value="{{ $feature->link }}" placeholder="Enter link">
                                        </div>

                                        <div class="form-group">
                                        
                                            <div class="row">
                                                <div class="col-8">
                                                    <label for="image"> Feature Image</label>
                                                    <div class="custom-file">
                                                        <input type="file" name="image" value="{{$feature->image}}" class="custom-file-input" id="image">
                                                        <label class="custom-file-label" for="image">Choose file</label>
                                                    </div>
                                                </div>
                                                <div class="col-4 text-right">
                                                    <div style="max-width: 100px; max-height: 100px;overflow:hidden; margin-left: auto">
                                                        <img src="{{ asset($feature->image) }}" class="img-fluid" alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        

                                        
                                       
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-lg btn-primary">Update feature</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection