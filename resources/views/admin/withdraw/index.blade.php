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
                            <h3 class="card-title">Withdraw List</h3>
                            @if(auth()->user()->is_admin==1)

                            @else
                            <a href="{{ route('withdraw.create') }}" class="btn btn-primary">Request Withdraw</a>
                            @endif
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0 px-3">
                        <table class="table table-striped" id="orderTable">
                            <thead>
                                <tr>
                                    <th>User Name</th>
                                  
                                    <th>Amount</th>

                                    <th>Payment Method</th>
                                    
                                    <th> Account Detail</th>  
                                 
                                  
                                       
                                    
                                    <th>Created date</th>

                                    <th>Payment Status</th> 
                                
                                </tr>
                            </thead>
                            <tbody>
                                
                                @foreach($withdraws as $withdraw)
                                <tr>
                                @if($withdraw->user)
                               <td> {{$withdraw->user->name}}</td>
                               @else
                               <td></td>
                              @endif
                             
                                <td>{{$withdraw->amount}}</td>
                               
                               <td>{{$withdraw->payment_method}}</td>
                            
                                <td>{{$withdraw->account_detail}}</td>
                              

                                <td>{{$withdraw->created_at}}</td>
                                <td>
                               @if(auth()->user()->is_admin == 1)
                                <form class="statusForm{{ $withdraw->id }}" action="{{ route('admin.withdraw.update', $withdraw->id) }}" method="POST" enctype="multipart/form-data">
                                 
                                    @csrf
                                    @method('PUT')
                                    <select class="status" name ="status" oid="{{ $withdraw->id }}">
                                        <option value="completed"  @if($withdraw->status=="completed")selected @endif>Completed</option>
                                        <option value ="processing"  @if($withdraw->status=="processing")selected @endif>Processing</option>
                
                                    </select>
                                    </form>
                                    @else
                                    {{$withdraw->status}}
                                    @endif
                                </td>
                              
                                
                             
                               
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
            "order": [[ 4, "desc"]]
        });
    })

    $('.status').on('change',function(e){
   var a = $(this).attr('oid');
   $('.statusForm'+a).submit();
})
    </script>

@endsection

