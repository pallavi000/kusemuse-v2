@extends('layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Create User</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">User list</a></li>
                    <li class="breadcrumb-item active">Create User</li>
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
        @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Create User</h3>
                            <a href="{{ route('users.index') }}" class="btn btn-primary">Go Back to User List</a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-12 col-lg-6 offset-lg-3 col-md-8 offset-md-2">
                                <form action="{{ route('users.store') }}" method="POST"  enctype="multipart/form-data" >
                                    @csrf
                                    <div class="card-body">
                                    
                                        <div class="form-group">
                                            <label for="name">User name</label>
                                            <input type="name" name="name" class="form-control" id="name" placeholder="Enter name">
                                        </div>
                                        <div class="form-group">
                                            <label for="email">User email</label>
                                            <input type="email" name="email" class="form-control" id="email" placeholder="Enter email">
                                        </div>
                                       
                                        <div class="form-group">
                                            <label for="password">User password</label>
                                            <input type="password" name="password" class="form-control" id="password" placeholder="Enter password">
                                        </div>
                                        <div class ="form-group">
                                            <label >Role</label>
                                            <select name="role" class="form-control brand_select" >
                                                <option  value="admin" >Admin</option>
                                                <option value="boutique_seller">Boutique Seller</option>
                                                <option value="brand_seller">Brand Seller</option>

                                                </select>
                                        </div>

                                        <div class="form-group brand_image" style="display:none;">
                                            <label for="image">Image</label>
                                            <div class="custom-file">
                                                <input type="file" name="image" class="custom-file-input" id="image">
                                                <label class="custom-file-label" for="image">Choose file</label>
                                            </div>
                                        </div>

                                        <div class="form-group brand_detail"  style="display:none;">
                                            <label> Brand detail</label>
                                            <textarea name="detail" class="form-control" rows='4'>
                                             </textarea>
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
</div>


<script>
    $('.brand_select').on('change',function(e){
        var a= ($(this).val());
        if(a== 'brand_seller'){
            $('.brand_image').show();
            $('.brand_detail').show();
        }else{
            $('.brand_image').hide();
            $('.brand_detail').hide();
        }
    })
    </script>
@endsection