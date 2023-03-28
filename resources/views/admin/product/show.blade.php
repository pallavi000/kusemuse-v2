@extends('layouts.admin')

@section('content')



<div class="content">
    <div class="container-fluid mx-auto">
     <h3> Product Detail</h3>
        <div class="row">
            <div class="col-md-6">
                <div class="card mx-auto">
                    <div class="card-body">
                    <img src="{{$product->image}}" class="img-fluid">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mx-auto">
                    <div class="card-body">
                    <p><strong>Name: {{ $product->name}}</strong></p>
                    <p><strong>Price: Rs.{{ $product->Price}}</strong></p>
                    <p><strong>Category: {{ $product->category->name}}</strong></p>
                    @if($product->brand)
                    <p><strong>Brand: {{ $product->brand->name}}</strong></p>
                    @endif
                    <p><strong>Color: {{ $product->color->name}}</strong></p>
                    <p><strong>Product Detail: {{ $product->description}}</strong></p>
                    <p><strong>Discount: {{ $product->discount}}%</strong></p>
                    @if($product->brand)
                    <p><strong>SKU: {{ $product->sku}}</strong></p>
                    @endif

                   


                    </div>
                </div>
            </div>
        </div>
</div>
</div>


@endsection