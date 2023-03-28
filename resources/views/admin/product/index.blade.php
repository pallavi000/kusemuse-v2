@extends('layouts.admin')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.1/css/dataTables.bootstrap4.min.css">
<script href="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap4.min.js"></script>




<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Product List</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                     @if(auth()->user()->is_admin==1)
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    @else
                    <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">Home</a></li>
                    @endif
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
                    <div class="card-header mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Product List</h3>
                           @if(auth()->user()->is_admin==1)
                            <a href="{{ route('admin.product.create') }}" class="btn btn-primary">Add Product</a>
                            @else
                            <a href="{{ route('product.create') }}" class="btn btn-primary">Add Product</a>
                            @endif
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0 px-3">
                        <table class="table table-striped" id="productTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                 
                                    <th>Name</th>
                                    <th>Category</th>
                                  
                                    <th>Stock</th>
                                   
                                    <th>Price</th>
                                    <th>Size</th>
                                    <th>Brand</th>
                                    <th>Discount</th>
                                    <th>Image</th>
                                    <th style="width: 40px">Action</th>
                               
                                </tr>
                            </thead>
                            <tbody>
                                @if($products->count())
                                @foreach ($products as $product)
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        
                                    
                                    <td>{{$product->name}}</td>
                                    @if($product->category)
                                    <td>{{ $product->category->name }}</td>
                                    @else<td> </td>
                                  @endif
                                    <td>{{$product->stock}}</td>
                                    <td>{{$product->price}}</td>
                                    
                                    <td>@foreach($product->sizes as $sizes)
                                    {{$sizes->name}}
                                    @endforeach
                                    </td>
                                    @if($product->brand)
                                    <td>{{$product->brand->name}}</td>
                                    @else<td></td>
                                    @endif
                                   
                                    
                                    <td>{{$product->discount}}</td>
                                    <td>
                                            <div style="max-width: 70px; max-height:70px;overflow:hidden">
                                                <img src="{{ asset($product->image) }}" class="img-fluid img-rounded" alt="">
                                            </div>
                                        </td>
                                      
                                
                     
                    
                                        <td class="d-flex">
                                        @if(auth()->user()->is_admin==1)
                                            <a href="{{ route('admin.product.show', [$product->id]) }}" class="btn btn-sm btn-success mr-1"> <i class="fas fa-eye"></i> </a>
                                            <a href="{{ route('admin.product.edit', [$product->id]) }}" class="btn btn-sm btn-primary mr-1"> <i class="fas fa-edit"></i> </a>
                                            <form action="{{ route('admin.product.destroy', [$product->id]) }}" class="mr-1" method="POST">
                                            @else
                                            <a href="{{ route('product.show', [$product->id]) }}" class="btn btn-sm btn-success mr-1"> <i class="fas fa-eye"></i> </a>
                                            <a href="{{ route('product.edit', [$product->id]) }}" class="btn btn-sm btn-primary mr-1"> <i class="fas fa-edit"></i> </a>

                                            <form action="{{ route('product.destroy', [$product->id]) }}" class="mr-1"  method="POST">
                                                @endif
                                                @method('DELETE')
                                                @csrf 
                                                <button type="submit" class="btn btn-sm btn-danger " onclick="return confirm('Are You Sure to delete this product?')"  > <i class="fas fa-trash"></i> </button>
                                            </form>
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
                   
                     <div class="card-footer d-flex justify-content-center">
                        {{ $products->links() }}
                   
                </div>
            </div>
        </div>
        </div>
    </div>
</div>

<script>
    $(document).ready( function () {
    $('#productTable').DataTable({
        "order": [[ 0, "desc" ]]
    });
} );
 </script>

@endsection
