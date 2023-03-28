@extends('layouts.admin')
@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Add Order</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('order.index') }}">Order list</a></li>
                    <li class="breadcrumb-item active">Add Order</li>
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
                            <h3 class="card-title">Add Order</h3>
                            <a href="{{ route('order.index') }}" class="btn btn-primary">Go Back to Order List</a>
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
                            <div class="col-12 col-lg-8 offset-lg-2 col-md-8 offset-md-2">
                                <form action="{{ route('order.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-body">
                                   
                                    <div class="form-group">
                                        <label for ="payment_status" >payment status</label>
                                            <input  type="text" name="payment_status" class="form-control" placeholder="payment status" >
                                            </div>
                                        

                                        <div class="form-group">
                                            <label for="exampleInputPassword1">detail</label>
                                          
                                            <textarea id="summernote" name="detail"></textarea>
                                        </div>
                                        <div class="form-group">
                                        <label for ="total" >Total</label>
                                            <input  type="text" name="total" class="form-control" placeholder="total" >
                                            </div>
                                 
                                    </div>

                                    <div class="card-footer">
                                        <input type="submit" class="btn btn-lg btn-primary" value="Submit" id="sub">
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




@section('script')


<script>
    $(document).ready(function() {
  $('#summernote').summernote();
});
</script>

    
@endsection