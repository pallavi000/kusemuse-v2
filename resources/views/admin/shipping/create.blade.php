@extends('layouts.admin')
@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Add Shipping</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('shipping.index') }}">Shipping List</a></li>
                    <li class="breadcrumb-item active">Add Shipping</li>
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
                            <h3 class="card-title">Add Shipping</h3>
                            <a href="{{ route('shipping.index') }}" class="btn btn-primary">Go Back to Shipping List</a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-12 col-lg-6 offset-lg-3 col-md-8 offset-md-2">
                                <form action="{{ route('shipping.store') }}" method="POST">
                                    @csrf
                                    <div class="card-body">
                                       
                                        <div class="form-group">

                                            <label for="location">Location</label>
	<select class="form-control" name="location" id="location" required>

      <option value="">Search Location</option>
	       @foreach($cities as $city)
		   <option value="{{$city->name}}" className="201">{{$city->name}}</option>

		   @endforeach
	</select>
                                           
                                        </div>  
                                        <div class="form-group">
                                            <label for="amount">Amount</label>
                                            <input type="name" name="amount" class="form-control" id="name" placeholder="Enter shipping fee">
                                        </div>  

                                        <div class="form-group">
                                            <label for="amount">Shipment days</label>
                                            <input type="" name="days" class="form-control" id="name" placeholder="Enter shipment days">
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
     $(document).ready(function () {
      $('#location').selectize({
          sortField: 'text'
      });
	});

</script>
@endsection