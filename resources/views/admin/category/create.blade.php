@extends('layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Create Category</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('category.index') }}">Category list</a></li>
                    <li class="breadcrumb-item active">Create Category</li>
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
                            <h3 class="card-title">Create Category</h3>
                            <a href="{{ route('category.index') }}" class="btn btn-primary">Go Back to Category List</a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="row justify-content-center">
                     
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
       
                            
                                <form action="{{ route('category.store') }}" class="w-50" method="POST" enctype="multipart/form-data">
                                <div class="col-md-12">
                                    @csrf
                                    <div class="card-body py-5">
                                      
                                        <div class="form-group">
                                            <label for="name">Category name</label>
                                            <input type="name" name="name" class="form-control" id="name" placeholder="Enter name" required>
                                        </div>
                                  
                                         <div class="form-group ">
                                            <label for="name">Parent</label>
                                            <select class="form-control" name="parent_id">
                                            <option value="0">None</option>
                                            @foreach($categories as $category)
                                            <option value="{{$category->id}}"> {{$category->name}}</option>
                                            @foreach ($category->childrenCategories as $childCategory)
                                                @include('admin.category.child_category', ['child_category' => $childCategory])
                                            @endforeach
                                            @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="image">Image</label>
                                            <div class="custom-file">
                                                <input type="file" name="image" class="custom-file-input" id="image">
                                                <label class="custom-file-label" for="image">Choose file</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Fill Info & Care attribute</label>
                                            <select class="form-control attr">
                                            <option>choose required column</option>
                                            @for($i=1;$i<=20;$i++)
                                            <option value="{{$i}}">{{$i}}</option>
                                            @endfor
                                            </select>
                                        </div>

                                   
                                       
                                        <div id="attr_parent">


                                        </div>

 












                                    </div>
                                   
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-lg btn-primary">Submit</button>
                                    </div>
                                  
                                </form>
                         
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    
    $('.attr').on('change',function(e){
    var attr_value = $(this).val();

     var input =  '<div class="form-group remove_attr"><label>Add Attribute Name</label><input name="attr[]"class="form-control" /></div>';

     $('.remove_attr').remove();

    for(var i=0; i<attr_value; i++) {
       $('#attr_parent').append(input);
    }

  });


</script>
@endsection