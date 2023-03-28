@extends('layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Edit Color</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('banner.index') }}">Banner list</a></li>
                    <li class="breadcrumb-item active">Edit Banner</li>
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
                            <h3 class="card-title">Edit Banner- {{ $banner->name }}</h3>
                            <a href="{{ route('banner.index') }}" class="btn btn-primary">Go Back to Banner List</a>
                           

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
                               
                                <form action="{{ route('banner.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                                   
                                    @csrf 
                                    @method('PUT')
                                    <div class="card-body">
                                  
                                        <div class="form-group">
                                            <label for="name">Banner title</label>
                                            <input type="name" name="title" class="form-control" value="{{ $banner->title }}" placeholder="Enter name">
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Banner Link</label>
                                            <input type="name" name="link" class="form-control" value="{{ $banner->link }}" placeholder="Enter link">
                                        </div>

                                        <div class="form-group">
                                            <label for="image">Image</label>
                                            <div class="custom-file">
                                                <input type="file" name="image" value="{{$banner->image}}" class="custom-file-input" id="image">
                                                <label class="custom-file-label" for="image">Choose file</label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="category">Banner Category</label>   
                                               
                                            <select name="category" id="category" class="form-control">
                                                <option value="{{$banner->category_id}}" style="display: none" selected>{{$banner->category->name}}</option>
                                                @foreach($categories as $category)
                                            <option value="{{$category->id}}"> {{$banner->name}}</option>
                                            @foreach ($category->childrenCategories as $childCategory)
                                                @include('admin.category.child_category', ['child_category' => $childCategory])
                                            @endforeach
                                            @endforeach
                                            </select>
                                        </div>
                                       
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-lg btn-primary">Update Banner</button>
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