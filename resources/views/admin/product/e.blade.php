@extends('layouts.admin')
@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Add Product</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('product.index') }}">Product list</a></li>
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
                            <a href="{{ route('product.index') }}" class="btn btn-primary">Go Back to Product List</a>
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
                                <form action="{{ route('product.update', [$product->id]) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="card-body">
                        <div class="product-tab" >
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
                                                    <option value="{{ $product->brand_id }}" style="display:none" selected>{{$product->brand->name}}</option>
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
                                            <label>Choose Product Size</label>
                                            <div class=" d-flex flex-wrap">
                                                @foreach($sizes as $size) 
                                                <div class="custom-control custom-checkbox" style="margin-right: 20px">
                            
                                                    <input class="custom-control-input" name="sizes[]" type="checkbox" id="size{{ $size->id}}" value="{{ $size->id }}"
                                                    @foreach($product->sizes as $s)
                                                        @if($size->id == $s->id) checked @endif
                                                    @endforeach
                                                    >
                                                    <label for="size{{ $size->id}}" class="custom-control-label">{{ $size->name }}</label>
                                                
                                                    <input class="form-control" type="text" name="prices[{{$size->id}}]" value="@foreach($product->sizes as $size_var)@if($size->id==$size_var->id){{$size_var->pivot->price}}@endif @endforeach"
                                                     placeholder="Price" disabled>
                                                    <input class="form-control" type="text" name="stocks[{{$size->id}}]" value="@foreach($product->sizes as $size_var)@if($size->id==$size_var->id){{$size_var->pivot->stock}}@endif @endforeach" placeholder="Stock" disabled>
                                                </div>
                                              
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Description</label>
                                            <textarea name="description" id="description" rows="4" class="form-control"
                                                placeholder="Enter description">{{ $product->description }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Choose Product Color</label>
                                            <div class=" d-flex flex-wrap">
                                                @foreach($colors as $color) 
                                                <div class="custom-control " style="margin-right: 20px">
                                                    <input class="form-check-input" name="colors" type="radio" id="color{{ $color->id}}"  value="{{ $color->id }}"
                                                    @foreach($product->colors as $c)
                                                    @if($color->id== $c->id) checked @endif
                                                    @endforeach
                                                    >
                                                    <label for="color{{ $color->id}}"  class="custom-control">{{ $color->name }}</label>
                                                </div>
                                                @endforeach
                                            </div>
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
                                                    <label for="image">Image</label>
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
                                            <label for="image">Feature Image</label>
                                            <div class="custom-file">
                                                <input type="file" name="feature_image[]" class="custom-file-input" id="image" multiple>
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
       $('.brands').hide();
       $('option[cate_id="'+id+'"]').show();

   })

    </script>


<script>

    $('input[name="sizes[]"]').on('change', function(e) {
        var id = $(this).val();
        if($(this).prop("checked")) {
            $('input[name="prices['+id+']"]').prop('disabled', false);
            $('input[name="stocks['+id+']"]').prop('disabled', false);
        } else {
            $('input[name="prices['+id+']"]').prop('disabled', true);
            $('input[name="stocks['+id+']"]').prop('disabled', true);           
        }
    })

</script>




@endsection




@section('script')


<script>
    $(document).ready(function() {
  $('#summernote').summernote();
});
</script>

    
@endsection