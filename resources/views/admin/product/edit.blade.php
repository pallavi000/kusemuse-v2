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
                <h1 class="m-0 text-dark">Add Product</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if(auth()->user()->is_admin== 1)
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.product.index') }}">Product list</a></li>
                    @else
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('product.index') }}">Product list</a></li>
                    @endif
                    <li class="breadcrumb-item active">Edit Product</li>
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
                            <h3 class="card-title">Edit Product - {{ $product->name }}</h3>
                            @if(auth()->user()->is_admin == 1)
                            <a href="{{ route('admin.product.index') }}" class="btn btn-primary">Go Back to Product List</a>
                            @else
                            <a href="{{ route('product.index') }}" class="btn btn-primary">Go Back to Product List</a>
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
                            <div class="col-12 col-lg-8 offset-lg-2 col-md-8 offset-md-2">
                                @if(auth()->user()->is_admin==1)
                                <form action="{{ route('admin.product.update', [$product->id]) }}" method="POST" enctype="multipart/form-data">
                                    @else
                                    <form action="{{ route('product.update', [$product->id]) }}" method="POST" enctype="multipart/form-data">
                                        @endif
                                    @csrf
                                    @method('PUT')
                                    <div class="card-body">
                        <div class="product-tab" >

                        @if(auth()->user()->is_admin==1)
                                    <div class = "form-group">
                                        <label>Select Seller</label>
                                        <select class="form-control" name="brand_seller">
                                            <option value="">none</option>
                                            @foreach($brand_seller as $seller)
                                            <option value="{{$seller->id}}" @if($product->seller_id==$seller->id) selected @endif> {{$seller->name}}</option>
                                            @endforeach
                                        </select>
                                        </div>
                                        @endif
                                        <div class="form-group">
                                            <label for="title">Product name</label>
                                            <input type="name" name="name" value="{{ $product->name }}" class="form-control" placeholder="Enter Name">
                                        </div>

                                        <div class="form-group">
                                            <label for="category">Product Category</label>   
                                               
                                            <select name="category" id="category" class="form-control">
                                                <option value="{{$product->category_id}}" style="display: none" selected>{{$product->category->name}}</option>
                                                @foreach($categories as $category)
                                            <option value="{{$category->id}}"> {{$category->name}}</option>
                                            @foreach ($category->childrenCategories as $childCategory)
                                                @include('admin.category.child_category', ['child_category' => $childCategory])
                                            @endforeach
                                            @endforeach
                                            </select>
                                        </div>

                                            <div class="form-group">
                                                <label for ="price" >Price</label>
                                                <input  type="text" name="price"  value="{{$product->price}}" class="form-control" placeholder="Enter price" >
                                            </div>
                                            <div class="form-group">
                                                 <label for ="discount" >Discount</label>
                                                <input  type="text" name="discount" value="{{$product->discount}}" class="form-control" placeholder="discount" >
                                            </div>
                                            <div class="btn btn-primary next-btn" current="product-tab" next-tab="attribute-tab">Next</button>

                                          </div>
                                          </div>
                     <div class="attribute-tab" style="display:none">

                                        <div class="form-group">
                                            <label for="category">Product brand</label> 
                                                    <select name= "brand" id="brand" class="form-control">  
                                                    @if($product->brand)
                                                    <option value="{{ $product->brand_id }}" selected>{{$product->brand->name}}</option>
                                                    @else
                                                    <option value="">Search brands</option>
                                                    @endif

                                                        @foreach($brands as $brand)

                                                        <option value="{{$brand->id}}">{{$brand->name}}</option>                                                      
                                                        @endforeach

                                                    </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="stock">Stock</label>
                                            <input type="text" name="stock" value="{{$product->stock}}"  class="form-control" placeholder="Enter quantity">
                                        </div>

                                        <div class="form-group">
                                            <label for="category">Product Sizes</label> 
                                            <div class="form-check">     
                                            <ul class="my_scroll">
                                            @foreach($sizes as $size) 
                                            <div>
                                            <input class="input-group-prepend form-check-input"  name="sizes[]" type="checkbox" id="size{{ $size->id}}" value="{{ $size->id }}"
                                            @foreach($product->sizes as $s)
                                                        @if($size->id == $s->id) checked @endif
                                                    @endforeach> &nbsp;{{$size->name}}

                                            
                                        </div>

                                            @endforeach  
                                            </ul>
                                            </div>
                                            </div>
                                        
                                        <div class="form-group">
                                            <label>Choose Product Size</label>
                                            <div class=" d-flex flex-wrap">
                                                @foreach($sizes as $size) 
                                                <div class="custom-control custom-checkbox togglesize{{$size->id}}" style="margin-right: 20px;display:none;">
                            
                                                
                                                    {{ $size->name }}
                                                
                                                    <input class="form-control" type="text"  name="prices[{{$size->id}}]" value="@foreach($product->sizes as $size_var)@if($size->id==$size_var->id){{$size_var->pivot->price}}@endif @endforeach"
                                                     placeholder="Price" disabled >
                                                    <input class="form-control" type="text" name="stocks[{{$size->id}}]" value="@foreach($product->sizes as $size_var)@if($size->id==$size_var->id){{$size_var->pivot->stock}}@endif @endforeach" placeholder="Stock" disabled  >

                                                    <input class="form-control" type="text" name="sku[{{$size->id}}]" value="@foreach($product->sizes as $size_var)@if($size->id==$size_var->id){{$size_var->pivot->sku}}@endif @endforeach" placeholder="SKU" disabled  >
                                                </div>
                                              
                                                @endforeach
                                            </div>
                                        </div>
                                        @if(count($product->sizes)>0)
                                        @else
                                        <div class="form-group product-sku">
                                            <label for="sku">Product SKU</label>
                                            <input type="text" name="product_sku" value="{{$product->sku}}"class="form-control" placeholder="Enter SKU code">
                                        </div>
                                        @endif

                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Description</label>
                                            <textarea name="description"  id="description" rows="4" class="form-control summernote"
                                                placeholder="Enter description">{{ $product->description }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Product Highlight</label>
                                            <textarea name="highlight"  id="description" rows="2" class="form-control summernote"
                                                placeholder="Enter description">{{ $product->highlight }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Choose Product Color</label>
                                            <select class="form-control" name="colors" >
                                                @if($product->color)
                                                <option value="{{$product->color_id}}">{{$product->color->name}}</option>
                                                @endif
                                                @foreach($colors as $color)
                                                    <option value="{{$color->id}}">{{$color->name}}</option>
                                                    @endforeach
                                                </select>
                                           
                                        </div>

                                       

                                        @foreach($attributes as $attr)
                                        <div class="form-group hidden" attr_show="{{$attr->category_id}}" style="display: none">
                                            <label for="title">{{$attr->attribute_id}}</label>
                                            <input type="name" name="attr[{{$attr->attribute_id}}]" value="" class="form-control" placeholder="Enter Name">
                                        </div>
                                        @endforeach

                                        <div class="btn btn-danger prev-btn" current="attribute-tab" prev-tab= "product-tab">Previous</div>
                                        <div class="btn btn-primary next-btn" current="attribute-tab" next-tab="variant-tab"> Next</div>
                                      </div>

                    <div class="variant-tab" style="display:none;">
                    <div class="form-group">
                                            <div class="row">
                                                <div class="col-8">
                                                    <label for="image">Product Feature Image</label>
                                                    <div class="custom-file">
                                                        <input type="file" name="image" class="custom-file-input" id="image">
                                                        <label class="custom-file-label" for="image">Choose file</label>
                                                    </div>
                                                </div>
                                                <div class="col-4 text-right">
                                                    <div style="max-width: 100px; max-height: 100px;overflow:hidden; margin-left: auto">
                                                        <img src="{{ asset($product->image) }}" class="img-fluid" alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                       
                                        <div class="form-group">
                                        <div class="row">
                                            <div class="col-8">
                                            <label for="image">Product Gallery Image</label>
                                            <div class="custom-file">
                                                <input type="file" name="feature_image[]" class="custom-file-input" id="image" multiple>
                                                <label class="custom-file-label" for="image">Choose file</label>
                                            </div>
                                        </div>

                                        @if(auth()->user()->is_admin==1)
                                            <div class="form-check">
                                                @if($product->feature== 'feature')

                                                 <input class="form-check-input" type="checkbox" name="feature" id="feature" value="feature" checked>
                                                 @else
                                                 <input class="form-check-input" type="checkbox" name="feature" id="feature" value="feature">
                                                 @endif

                                                <label class="form-check-label" for="feature">
                                                Feaure product
                                                </label>
                                             </div>
                                            @endif

                                        <div class="col-4 text-right">
                                                    <div style="max-width: 100px; max-height: 100px;overflow:hidden; margin-left: auto">
                                                        <img src="{{ asset($product->image) }}" class="img-fluid" alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="btn btn-danger prev-btn" current="variant-tab" prev-tab="attribute-tab">Previous</div>
                                        <input type="submit" class="btn  btn-primary" value="Submit" id="sub">
                                     </div>
                                  

                                    <!-- <div class="card-footer">
                                        <input type="submit" class="btn btn-lg btn-primary" value="Submit" id="sub">
                                    </div> -->

                                    
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
$(document).ready(function(e){

    $('input[name="sizes[]"]').each(function(index, element) {
        if( $(element).is(":checked")) {
            var id = $(element).val();
            $('.togglesize'+id).show();
            $('input[name="prices['+id+']"]').prop('disabled', false);
            $('input[name="stocks['+id+']"]').prop('disabled', false);
            $('input[name="sku['+id+']"]').prop('disabled',false); 
        }
    })

    var sizes = {!! json_encode($product->sizes) !!};

    if(sizes.length>0) {
        $('.product-sku').hide()

    } else {
        $('.product-sku').show()
    }


    $('.next-btn').on('click',function(){
   var next_tab = $(this).attr("next-tab");
    var pro =$(this).attr("current");
   $('.'+ pro).hide();
    $('.'+ next_tab).show();
     
    })

    $('.prev-btn').on('click',function(){
        var prev_tab = $(this).attr("prev-tab");
        var pro =$(this).attr("current");
      $('.'+pro).hide();
        $('.'+prev_tab).show();
    })
})
</script>


<script>
   $('input[name="category"]').on('change',function(e){
       var id = $(this).val();
       $('.hidden').hide();
       $('div[attr_show="'+id+'"]').show();
       $('input[name="prices['+id+']"]').prop('disabled', false);
            $('input[name="stocks['+id+']"]').prop('disabled', false); 
            $('input[name="sku['+id+']"]').prop('disabled', false);
   })

   $(document).ready(function () {
      $('#brand').selectize({
          sortField: 'text'
      });
  });
    </script>


<script>

    $('input[name="sizes[]"]').on('change', function(e) {
        var id = $(this).val();
        if($(this).prop("checked")) {
            $('.product-sku').hide()
            $('.togglesize'+id).show();
            $('input[name="prices['+id+']"]').prop('disabled', false);
            $('input[name="stocks['+id+']"]').prop('disabled', false);
            $('input[name="sku['+id+']"]').prop('disabled',false);

        } else {
            $('.togglesize'+id).hide();
            $('.product-sku').show()
            $('input[name="prices['+id+']"]').prop('disabled', true);
            $('input[name="stocks['+id+']"]').prop('disabled', true);
            $('input[name="sku['+id+']"]').prop('disabled',true);   
    
          
        }
    })

</script>




@endsection




@section('script')


<script type="text/javascript">
        $(document).ready(function() {
          $('.summernote').summernote();
        });
    </script>

    
@endsection