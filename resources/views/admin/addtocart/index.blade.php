@extends('layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Product List</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Product list</li>
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
                            <h3 class="card-title">Product List</h3>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                  
                                    <th>Product Name</th>
                                    <th>User Name</th>
                                    <th>Category</th>
                                    <th>Description</th>
                                    <th>quantity</th>
                                    <th>Price</th>
                                    <th>Discount</th>
                                    <th>Image</th>
                                    <th style="width: 40px">Action</th>
                               
                                </tr>
                            </thead>
                            <tbody>
                                @if($addtocarts->count())
                                @foreach ($addtocarts as $addtocart)
                                    <tr>
                                        <td>{{ $addtocart->id }}</td>
                                    
                                    <td>{{$addtocart->product->name}}</td>
                                    <td>{{$addtocart->user->name}}</td>

                                    <td>{{ $addtocart->category->name }}</td>
                                    <td>{{$addtocart->product->description}}</td>
                                    <td>{{$addtocart->quantity}}</td>
                                    
                                    <td>{{$addtocart->product->price}}</td>
                                    <td>{{$addtocart->product->discount}}</td>
                                    <td>
                                            <div style="max-width: 70px; max-height:70px;overflow:hidden">
                                                <img src="{{ asset($addtocart->product->image) }}" class="img-fluid img-rounded" alt="">
                                            </div>
                                        </td>
                                      
                                
                     
                    
                                       
                                    </tr>
                                @endforeach
                                @else   
                                    <tr>
                                        <td colspan="12">
                                            <h5 class="text-center">No products found.</h5>
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