@extends('layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Coupon List</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if(auth()->user()->is_admin==1)
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    @else
                    <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">Home</a></li>
                    @endif
                    <li class="breadcrumb-item active">Coupon list</li>
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
                            <h3 class="card-title">Coupon List</h3>
                            @if(auth()->user()->is_admin==1)
                            <a href="{{ route('admin.coupon.create') }}" class="btn btn-primary">Create Coupon</a>
                            @else
                            <a href="{{ route('coupon.create') }}" class="btn btn-primary">Create Coupon</a>
                            @endif
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th> Coupon Title</th>
                                    <th> Product Name</th>
                                    <th>Coupon</th>
                                    <th>Expire date</th>
                                    <th>Type</th>
                                    <th>Value</th>
                                    <th>Minimum Spend</th>
                                    <th>Maximum Spend</th>
                                    <th>Usages Limit</th>

                                 
                                    <th style="width: 40px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($coupons as $coupon)
                               <tr>
                                        <td>{{ $coupon->id }}</td>
                                        <td>{{$coupon->title}}</td>
                                        <td> {{$coupon->product->id}} {{$coupon->product->name}}</td>
                                        <td class="text-uppercase">{{$coupon->coupon_code}}</td>
                                        <td>{{$coupon->expire_date}}</td>
                                        <td>{{$coupon->type}}</td>
                                        <td>{{$coupon->value}}</td>
                                        <td>{{$coupon->minimumSpend}}</td>
                                        <td>{{$coupon->maximumSpend}}</td>
                                        <td>{{$coupon->usagesLimit}}</td>
                                        <td class="d-flex">
                                            @if(auth()->user()->is_admin==1)
                                            <a href="{{ route('admin.coupon.edit', [$coupon->id]) }}" class="btn btn-sm btn-primary mr-1"> <i class="fas fa-edit"></i> </a>
                                            @else
                                            <a href="{{ route('coupon.edit', [$coupon->id]) }}" class="btn btn-sm btn-primary mr-1"> <i class="fas fa-edit"></i> </a>
                                            @endif
                                            @if(auth()->user()->is_admin==1)
                                            <form action="{{ route('admin.coupon.destroy', [$coupon->id]) }}" class="mr-1" method="POST">
                                            @method('DELETE')
                                                @csrf 
                                                <button type="submit" class="btn btn-sm btn-danger"> <i class="fas fa-trash"></i> </button>
                                                @else
                                                <form action="{{ route('coupon.destroy', [$coupon->id]) }}" class="mr-1" method="POST">
                                                @method('DELETE')
                                                @csrf 
                                                <button type="submit" class="btn btn-sm btn-danger"> <i class="fas fa-trash"></i> </button>
                                                @endif

                                               
                                            </form>
                                        </td>
                                 </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection