@extends('layouts.admin')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.1/css/dataTables.bootstrap4.min.css">
<script href="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap4.min.js"></script>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
            <div class="card  ">
                    <div class="card-header mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Order List</h3>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0 px-3">
                        <table class="table table-striped" id="orderTable">
                            <thead>
                                <tr>
                                    <th>User Name</th>
                                  
                                    <th>payment Method</th>

                                    <th>Amount</th>
                                    
                                    <th> Date</th>                               
                                </tr>
                            </thead>
                            <tbody>
                                
                                @foreach($orders as $order)
                                <tr>
                                    @if(auth()->user()->is_admin==1)
                                @if($order->user)
                               <td><a href="{{route('admin.order.show', [$order->id])}}" class="nav-link mr-1">#{{$order->id}} {{$order->user->name}}</a></td>
                                @else
                                <td><a href="{{route('admin.order.show', [$order->id])}}" class="nav-link mr-1">#{{$order->id}}</a></td>
                                @endif
                                @else
                                @if($order->user)
                               <td><a href="{{route('order.show', [$order->id])}}" class="nav-link mr-1">#{{$order->id}} {{$order->user->name}}</a></td>
                                @else
                                <td><a href="{{route('order.show', [$order->id])}}" class="nav-link mr-1">#{{$order->id}}</a></td>
                                @endif
                                @endif
                                
                               <td>{{$order->payment_method}}</td>
                               @php
                               
                                $total = 0;
                                if(auth()->user()->is_admin==1){
                                foreach($order->orders as $o) {
                                $total += $o->total;
                                }
                                }else{
                                 foreach($order->seller_orders as $o) {
                                $total += $o->total;
                                }
                                }
                                
                              @endphp
                               <td>{{$total}}</td>
                               <td>{{$order->created_at}}</td>
                                </tr>
                               @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#orderTable').DataTable({
            "order": [[ 3, "desc"]]
        });
    })
    </script>

@endsection

