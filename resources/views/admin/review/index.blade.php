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
                            <h3 class="card-title">Review List</h3>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0 px-3">
                        <table class="table table-striped" id="orderTable">
                            <thead>
                                <tr>
                                    <th>User Name</th>
                                  
                                    <th>Product Name</th>

                                    <th>Comment</th>
                                    
                                    <th> Rating</th>  
                                 
                                    <th>Created date</th>
                                    <th>Status</th>                            
                                </tr>
                            </thead>
                            <tbody>
                                
                                @foreach($reviews as $review)
                                <tr>
                                @if($review->user)
                               <td> {{$review->user->name}}</td>
                               @else
                               <td></td>
                              @endif
                              @if($review->product)
                                <td>{{$review->product->name}}</td>
                                @else 
                                <td></td>
                               @endif
                               <td>{{$review->comment}}</td>
                            
                                <td>{{$review->rating}}</td>
                              
                                <td>{{$review->created_at}}</td>
                                <td>
                               
                                <form class="statusForm{{ $review->id }}" action="{{ route('review.update', $review->id) }}" method="POST" enctype="multipart/form-data">
                                 
                                    @csrf
                                    @method('PUT')
                                    <select class="status" name ="status" oid="{{ $review->id }}">
                                        <option value="publish"  @if($review->status=="publish")selected @endif>Publish</option>
                                        <option value ="unpublish"  @if($review->status=="unpublish")selected @endif>unPublish</option>
                
                                    </select>
                                    </form>
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

