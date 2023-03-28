@extends('layouts.admin')

@section('content')



<div class="content">
    <div class="container-fluid mx-auto">
     <h3> User Detail</h3>
        <div class="row">
            <div class="col-md-6">
                <div class="card mx-auto">
                    <div class="card-body">
                    <p><strong>Name: {{ $users->name}}</strong></p>
                    <p><strong>Email: {{ $users->email}}</strong></p>
                    @if($users->customer)
                    <p><strong>Shipping address: {{ $users->customer->provience}}</strong></p>

                  
                    <p><strong> {{ $users->sku}}</strong></p>
                    @endif

                   


                    </div>
                </div>
            </div>
        </div>
</div>
</div>


@endsection