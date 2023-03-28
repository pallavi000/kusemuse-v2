@extends('layouts.admin')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Edit Coupon</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('coupon.index') }}">Brand list</a></li>
                    <li class="breadcrumb-item active">Add Coupon</li>
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
                            <h3 class="card-title">Edit Coupon </h3>
                            @if(auth()->user()->is_admin==0)
                            <a href="{{ route('coupon.index') }}" class="btn btn-primary">Go Back to Coupon List</a>
                            @else
                            <a href="{{ route('admin.coupon.index') }}" class="btn btn-primary">Go Back to Coupon List</a>

                            @endif
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
                            @if(auth()->user()->is_admin==1)
                                <form action="{{ route('admin.coupon.update',[$coupon->id]) }}" method="Post" enctype="multipart/form-data">
                                    @else
                                    <form action="{{ route('coupon.update',[$coupon->id]) }}" method="Post" enctype="multipart/form-data">
                                    @endif
                                    @csrf
                                    @method('PUT')
                                    <div class="card-body">
                                      
                                       

                                         <div class="form-group ">
                                            <label for="name">Product name</label>
                                            <select name="product_id">
                                            @if($coupon->product)
                                            <option value="{{ $coupon->product_id }}" selected>{{$coupon->product->name}}</option>

                                            @else
                                            <option value="">Search product</option>
                                            @endif
                                         @foreach($products as $product)
                                         <option value="{{$product->id}}">{{$product->name}}</option>
                                         @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Expire Date</label>
                                            <input type="name" id = "datepicker" name="expire_date" value="{{$coupon->expire_date}}" class="form-control" id="name" placeholder="Enter expire date">
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Coupon Type</label>
                                            <select  name="type">
                                                @if($coupon->type)
                                                <option value="{{ $coupon->type }}" selected>{{$coupon->type}}</option>
                                                @endif
                                            <option value="cash">Cash</option>
                                            <option value="discount">Discount %</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="name">Coupon Value(amount/percentage)</label>
                                            <input type="name" name="value" class="form-control" id="name" value="{{$coupon->value}}" placeholder="Enter coupon value">
                                        </div>

                                        <div class="form-group">
                                            <label for="name">Minimum  Spend</label>
                                            <input type="name" name="minimumSpend" class="form-control" id="name" value="{{$coupon->minimumSpend}}" placeholder="Enter minimum spend limit">
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Maximun Spend</label>
                                            <input type="name" name="maximumSpend" class="form-control" id="name" value="{{$coupon->maximumSpend}}" placeholder="Enter name maximum spend limit">
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Usages Limit</label>
                                            <input type="name" name="limit" class="form-control" id="name" value="{{$coupon->usagesLimit}}" placeholder="Enter coupon user limit">
                                        </div>
                                        <div class ="form-group">
                                        <label>Coupon use</label>
                                        @if($coupon->single == 1)
                                            <input type="checkbox"  name="single" value="1" checked> Individual use
                                            @else
                                            <input type="checkbox"  name="single" value="1" > Individual use

                                            @endif
                                        </div>
                                    </div>
                                   

                                    <div class="card-footer">
                                        <button type="submit"  class="btn btn-lg btn-primary">Submit</button>
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
  $( function() {
    $( "#datepicker" ).datepicker({
        dateFormat: "yy-mm-dd"
    });
   
  } );


 $(document).ready(function () {
      $('select').selectize({
          sortField: 'text'
      });
  });
  </script>
@endsection