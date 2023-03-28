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
                <h1 class="m-0 text-dark">Add Coupon</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if(auth()->user()->is_admin==1)
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.coupon.index') }}">Coupon list</a></li>
                    @else
                    <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('coupon.index') }}">Coupon list</a></li>
                    @endif
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
                            <h3 class="card-title">Create Coupon</h3>
                            @if(auth()->user()->is_admin==1)
                            <a href="{{ route('admin.coupon.index') }}" class="btn btn-primary">Go Back to Coupon List</a>
                            @else
                            <a href="{{ route('coupon.index') }}" class="btn btn-primary">Go Back to Coupon List</a>
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
                                <form action="{{ route('admin.coupon.store') }}" method="POST" enctype="multipart/form-data">
                                @else
                                <form action="{{ route('coupon.store') }}" method="POST" enctype="multipart/form-data">
                                    @endif
                                    @csrf
                                    <div class="card-body">
                                    <div class="form-group ">
                                            <label for="name">Product name</label>
                                            <select name="product_id" placeholder="search product">
                                            <option value="">Search product</option>
                                         @foreach($products as $product)
                                         <option value="{{$product->id}}">{{$product->name}}</option>
                                         @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Coupon Title</label>
                                            <input type="name"  name="title" class="form-control" id="name" placeholder="Enter title">
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Expire Date</label>
                                            <input type="name" id = "datepicker" name="expire_date" class="form-control" id="name" placeholder="Enter expire date">
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Coupon Type</label>
                                            <select  name="type">
                                            <option value="cash">Cash</option>
                                            <option value="discount">Discount %</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="name">Coupon Value(amount/percentage)</label>
                                            <input type="name" name="value" class="form-control" id="name" placeholder="Enter coupon value">
                                        </div>

                                        <div class="form-group">
                                            <label for="name">Minimum  Spend</label>
                                            <input type="name" name="minimumSpend" class="form-control" id="name" placeholder="Enter minimum spend limit">
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Maximun Spend</label>
                                            <input type="name" name="maximumSpend" class="form-control" id="name" placeholder="Enter name maximum spend limit">
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Usages Limit</label>
                                            <input type="name" name="limit" class="form-control" id="name" placeholder="Enter coupon user limit">
                                        </div>
                                        <div class ="form-group">
                                        <label>Coupon use</label>
                                            <input type="checkbox" name="single" value="1"> Individual use
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
<option>slf</option>
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




// $('.searchproduct').on('keyup', function(e) {
//     var query = $(this).val();
//     if(query.length>=2) {
//         $('.abc option').each(function(){
//             var searchline = $(this).text();
//             var a = searchline.indexOf(query);
//             if(a === -1) {
//                 $(this).hide();
//             } else {
//                 $(this).show();
//                 console.log(a);
//             }
//         })
//     } else {
//         $('.abc option').hide();
//     }

// })


</script>
@endsection