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
                    @if(auth()->user()->is_admin ==1)
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.product.index') }}">Product list</a></li>
                    <li class="breadcrumb-item active">Add Product</li>
                    @else
                    <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('product.index') }}">Product list</a></li>
                    <li class="breadcrumb-item active">Add Product</li>
                    @endif
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
                            <h3 class="card-title">Create Product</h3>
                            @if(auth()->user()->is_admin ==1)
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
                                <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data">
                                    @else
                                    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                                        @endif
                                
                                    @csrf
                                    <div class="card-body">
                        <div class="product-tab" >

                        @if(auth()->user()->is_admin==1)
                                    <div class = "form-group">
                                        <label>Select Seller</label>
                                        <select class="form-control" name="brand_seller">
                                            <option value="">none</option>
                                            @foreach($brand_seller as $seller)
                                            <option value="{{$seller->id}}">{{$seller->name}}</option>
                                            @endforeach
                                        </select>
                                        </div>
                                        @endif

                                        <div class="form-group">
                                            <label for="title">Product name</label>
                                            <input type="name" name="name" value="{{ old('name') }}" class="form-control" placeholder="Enter Name">
                                        </div>
                                        <div class="form-group">
                                            <label for="category">Product Category</label> 
                                            <div class="form-check">     
                                            <ul class="my_scroll">
                                                @foreach ($categories as $category)
                                                    <input type="radio" class="form-check-input " name="category"  value="{{$category->id}}"> {{ $category->name }}
                                                    <ul>
                                                    @foreach ($category->childrenCategories as $childCategory)
                                                        @include('admin.category.child_category', ['child_category' => $childCategory, 'type'=>'radio'])
                                                    @endforeach
                                                    </ul>
                                                @endforeach
                                            </ul>
                                            </div>
                                        </div>  
                                        <div class="form-group">
                                            <label for="category">Product brand</label> 
                                                    <select name= "brand" id="brand"  placeholder="search brand" class="form-control">    
                                                    <option value="">Search Brand</option>                                                        
                                                    @foreach($brands as $brand)
                                                        <option class="brands" cate_id="{{$brand->category_id}}" value="{{$brand->id}}">{{$brand->name}}</option>                                                       
                                                         @endforeach
                                                    </select>
                                        </div>

                                            <div class="form-group">
                                                <label for ="price" >Price</label>
                                                <input  type="text" name="price" class="form-control" placeholder="Enter price" >
                                            </div>
                                            <div class="form-group">
                                            <label for="stock">Stock</label>
                                            <input type="text" name="stock"  class="form-control" placeholder="Enter quantity">
                                        </div>
                                            <div class="form-group">
                                                 <label for ="discount" >Discount</label>
                                                <input  type="text" name="discount"  class="form-control" placeholder="discount" >
                                            </div>
                                            <div class="btn btn-primary next-btn" current="product-tab" next-tab="attribute-tab">Next</button>
                                          </div>
                                          </div>
                     <div class="attribute-tab" style="display:none">
                                 <div class="form-group">
                                            <label for="category">Product Sizes</label> 
                                            <div class="form-check">     
                                            <ul class="my_scroll">
                                            @foreach($sizes as $size) 
                                            <div>
                                            <input class="input-group-prepend form-check-input"  name="sizes[]" type="checkbox" id="size{{ $size->id}}" value="{{ $size->id }}"> &nbsp;{{$size->name}}
                                        </div>
                                            @endforeach  
                                            </ul>
                                            </div>
                                            </div>
                                        <div class="form-group">
                                                <label>Choose Product Size</label>
                                            <div class=" d-flex flex-wrap">
                                                @foreach($sizes as $size) 
                                                <div class="input-group mb-2 mr-sm-2 togglesize{{$size->id}}" style="display: none">
                                                    <div class="input-group-text">
                                                    {{$size->name}}
                                                    </div>
                                                    <input class="form-control" type="text" name="prices[{{$size->id}}]" placeholder="Price" disabled >
                                                    <input class="form-control" type="text" name="stocks[{{$size->id}}]" placeholder="Stock" disabled>
                                                    <input class="form-control" type="text" name="sku[{{$size->id}}]" placeholder="Product SKU code" disabled>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="form-group product-sku">
                                            <label for="sku">Product SKU</label>
                                            <input type="text" name="product_sku"  class="form-control" placeholder="Enter SKU code">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Description</label>
                                            <textarea class="summernote" name="description"></textarea>                          
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Product Highlight</label>
                                            <textarea class="summernote" name="highlight"></textarea>                          
                                        </div>

                                        <div class="form-group">
                                            <label>Choose Product Color</label>
                                            <select class="form-control" name="colors">
                                                <option value="">Select Color</option>
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
                                            <label for="image">Feature Product Image</label>
                                            <div class="custom-file">
                                                <input type="file" name="image" class="custom-file-input" id="image">
                                                <label class="custom-file-label" for="image">Choose file</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="image">Product Gallery Image</label>
                                            <div class="custom-file">
                                                <input type="file" name="feature_image[]" class="custom-file-input" id="image" multiple>
                                                <label class="custom-file-label" for="image">Choose file</label>
                                            </div>
                                        </div>
                                        @if(auth()->user()->is_admin==1)
                                        <div class="form-group">
                                            <label for="feature">Feature This Product</label>
                                            <div class="form-check">
                                                 <input class="form-check-input" type="checkbox" name="feature" id="feature" value="feature">
                                                <label class="form-check-label" for="feature">
                                                Yes
                                                </label>
                                             </div>
                                        </div>
                                            @endif
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

   })

    </script>


<script>

    $('input[name="sizes[]"]').on('change', function(e) {
        var id = $(this).val();
        if($(this).prop("checked")) {
           $('.product-sku').hide()
            $('input[name="prices['+id+']"]').prop('disabled', false);
            $('input[name="stocks['+id+']"]').prop('disabled', false);
            $('input[name="sku['+id+']"]').prop('disabled',false);
            
            $('.togglesize'+id).show();
        } else {
            $('.togglesize'+id).hide();
            $('.product-sku').show()
            $('input[name="prices['+id+']"]').prop('disabled', true);
            $('input[name="stocks['+id+']"]').prop('disabled', true);
            $('input[name="sku['+id+']"]').prop('disabled',true);          
        }
    })


    $(document).ready(function () {
      $('#brand').selectize({
          sortField: 'text'
      });
  });

</script>
<script type="text/javascript">
        $(document).ready(function() {
          $('.summernote').summernote();
        });
    </script>



@endsection




@section('script')




    
@endsection