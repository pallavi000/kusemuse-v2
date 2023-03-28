@extends('layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Edit Shipping</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('shipping.index') }}">Shipping list</a></li>
                    <li class="breadcrumb-item active">Edit Shipping</li>
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
                            <h3 class="card-title">Edit Shipping - {{ $shipping->location }}</h3>
                            <a href="{{ route('shipping.index') }}" class="btn btn-primary">Go Back to Shipping List</a>
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
                                <form action="{{ route('shipping.update', $shipping->id) }}" method="POST">
                                    @csrf 
                                    @method('PUT')
                                    <div class="card-body">
                                  
                                        <div class="form-group">
                                            <label for="name">Shipping location</label>
                                            <input type="name" name="location" class="form-control" value="{{ $shipping->location }}" placeholder="Enter shipping location">
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Shipping amount</label>
                                            <input type="name" name="amount" class="form-control" value="{{ $shipping->amount }}" placeholder="Enter shipping fee">
                                        </div>
                                        <div class="form-group">
                                            <label for="amount">Shipment Days</label>
                                            <input type="" name="days" class="form-control" id="name" placeholder="Enter Shipment Days">
                                        </div>  
                                        
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-lg btn-primary">Update Shipping</button>
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