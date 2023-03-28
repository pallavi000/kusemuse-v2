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
                <h1 class="m-0 text-dark">Customer List</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Customer list</li>
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
                            <h3 class="card-title">Customer List</h3>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0 px-3">
                        <table class="table table-striped"id="customerTable">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Provience</th>
                                    <th>District</th>
                                    <th>City</th>
                                    <th>Street</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($users->count())
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                       
                                    
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        @if($user->customer)
                                        <td>{{ $user->customer->phone}}</td>
                                        @else
                                         <td></td>
                                        @endif
                                      
                                        @if($user->customer)
                                        <td>{{ $user->customer->provience}}</td>
                                        @else
                                         <td></td>
                                        @endif
                                      
                                        @if($user->customer)
                                        <td>{{ $user->customer->district}}</td>
                                        @else
                                         <td></td>
                                        @endif
                                       
                                        @if($user->customer)
                                        <td>{{ $user->customer->city}}</td>
                                        @else
                                         <td></td>
                                        @endif
                                
                                        @if($user->customer)
                                        <td>{{ $user->customer->street}}</td>
                                        @else
                                         <td></td>
                                        @endif
                                   
                                        <td class="d-flex">
                                            <form action="{{ route('customer.destroy', [$user->id]) }}" class="mr-1" method="POST">
                                                @method('DELETE')
                                                @csrf 
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are You Sure to remove this Customer?')"> <i class="fas fa-trash"></i> </button>
                                            </form>
                                        </td>
                                       
                                    </tr>
                                @endforeach
                                @else   
                                    <tr>
                                        <td colspan="5">
                                            <h5 class="text-center">No users found.</h5>
                                        </td>
                                    </tr>

                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    
                </div>
            </div>
        </div>




    <!-- Seller List -->

       
    </div>
</div>

<script>
   $(document).ready( function () {
    $('#customerTable').DataTable();
} );
 </script>
@endsection