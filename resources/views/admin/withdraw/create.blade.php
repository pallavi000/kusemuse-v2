@extends('layouts.admin')
@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Request Withdraw</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('withdraw.index') }}">withdraw List</a></li>
                    <li class="breadcrumb-item active">Withdraw</li>
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
                            <h3 class="card-title">Request Withdraw</h3>
                            <a href="{{ route('withdraw.index') }}" class="btn btn-primary">Go Back to withdraw List</a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-12 col-lg-6 offset-lg-3 col-md-8 offset-md-2">
                                <form action="{{ route('withdraw.store') }}" method="POST">
                                    @csrf
                                    <div class="card-body">
                                    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
                                        <div class="form-group">

                                            <label for="location">Payment Method</label>
                                            <select class="form-control" name="payment_method" id="location" required>
                                                <option value="">Please Select Payment Method</option>
                                                <option value="esewa">Esewa</option>
                                                <option value="khalti">Khalti</option>

                                            </select>
                                           
                                        </div>  
                                        <div class="form-group">
                                            <label for="amount">Amount</label>
                                            <input type="number" min=100 name="amount" class="form-control" id="name" placeholder="Enter withdraw amount" required>
                                        </div>  

                                        <div class="form-group">
                                            <label for="amount">Account Detail</label>
                                            <input type="" name="detail" class="form-control" id="name" placeholder="Enter Your account Detail" required>
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