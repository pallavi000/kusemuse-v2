@extends('layouts.admin')
@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Add Boutique</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if(auth()->user()->is_admin==1)
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.boutique.index') }}">Boutique list</a></li>
                    @else
                    <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('boutique.index') }}">Boutique list</a></li>
                    @endif
                    <li class="breadcrumb-item active">Edit Boutique</li>
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
                            <h3 class="card-title">Edit Boutique - {{ $boutique->name }}</h3>
                            @if(auth()->user()->is_admin==1)
                            <a href="{{ route('admin.boutique.index') }}" class="btn btn-primary">Go Back to Boutique List</a>
                            @else
                            <a href="{{ route('boutique.index') }}" class="btn btn-primary">Go Back to Boutique List</a>
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
                                <form action="{{ route('admin.boutique.update', [$boutique->id]) }}" method="POST" enctype="multipart/form-data">
                                @else
                                <form action="{{ route('boutique.update', [$boutique->id]) }}" method="POST" enctype="multipart/form-data">
                                 @endif
                                    @csrf
                                    @method('PUT')
                                    <div class="card-body">
                        <div class="boutique-tab" >
                                        <div class="form-group">
                                            <label for="title">Boutique name</label>
                                            <input type="name" name="name" value="{{ $boutique->name }}" class="form-control" placeholder="Enter Name">
                                        </div>
                                        <div class="form-group">
                                            <label for="category">Boutique Category</label>   
                                               
                                            <select name="category" id="category" class="form-control">
                                                <option value="{{$boutique->category_id}}" style="display: none" selected>{{$boutique->category->name}}</option>
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
                                                <input  type="text" name="price"  value="{{$boutique->price}}" class="form-control" placeholder="Enter price" >
                                            </div>
                                            <div class="form-group">
                                                 <label for ="discount" >Discount</label>
                                                <input  type="text" name="discount" value="{{$boutique->discount}}" class="form-control" placeholder="discount" >
                                            </div>
                                            <div class="btn btn-primary next-btn" current="boutique-tab" next-tab="attribute-tab">Next</button>

                                          </div>
                                          </div>
                     <div class="attribute-tab" style="display:none">

                                        <div class="form-group">
                                            <label for="category">Boutique designer</label> 
                                                    <select name= "designer" id="designer" class="form-control">    
                                                    <option value="{{ $boutique->designer_id }}" style="display:none" selected>{{$boutique->designer->name}}</option>
                                                        @foreach($designers as $designer)

                                                        <option value="{{$designer->id}}">{{$designer->name}}</option>                                                      
                                                        @endforeach
                                                    </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="stock">Stock</label>
                                            <input type="text" name="stock" value="{{$boutique->stock}}"  class="form-control" placeholder="Enter quantity">
                                        </div>
                                        <div class="form-group">
                                            <label for="category">Boutique Sizes</label> 
                                            <div class="form-check">     
                                            <ul class="my_scroll">
                                            @foreach($sizes as $size) 
                                            <div>
                                            <input class="input-group-prepend form-check-input"  name="sizes[]" type="checkbox" id="size{{ $size->id}}" value="{{ $size->id }}"
                                            @foreach($boutique->sizes as $s)
                                                        @if($size->id == $s->id) checked @endif
                                                    @endforeach> &nbsp;{{$size->name}}
                                            
                                        </div>
                                            @endforeach  
                                            </ul>
                                            </div>
                                            </div>
                                        
                                        <div class="form-group">
                                            <label>Choose Boutique Size</label>
                                            <div class=" d-flex flex-wrap">
                                                @foreach($sizes as $size) 
                                                <div class="custom-control custom-checkbox togglesize{{$size->id}}" style="margin-right: 20px;display:none;">
                            
                                                
                                                    {{ $size->name }}
                                                
                                                    <input class="form-control" type="text" name="prices[{{$size->id}}]" value="@foreach($boutique->sizes as $size_var)@if($size->id==$size_var->id){{$size_var->pivot->price}}@endif @endforeach"
                                                     placeholder="Price" disabled >
                                                    <input class="form-control" type="text" name="stocks[{{$size->id}}]" value="@foreach($boutique->sizes as $size_var)@if($size->id==$size_var->id){{$size_var->pivot->stock}}@endif @endforeach" placeholder="Stock" disabled  >

                                                    <input class="form-control" type="text" name="sku[{{$size->id}}]" value="@foreach($boutique->sizes as $size_var)@if($size->id==$size_var->id){{$size_var->pivot->sku}}@endif @endforeach" placeholder="SKU" disabled  >
                                                </div>
                                              
                                                @endforeach
                                            </div>
                                        </div>
                                        
                                        @if(count($boutique->sizes)>0)
                                        @else
                                        <div class="form-group product-sku">
                                            <label for="sku">Boutique SKU</label>
                                            <input type="text" name="product_sku" value="{{$boutique->sku}}"class="form-control" placeholder="Enter SKU code">
                                        </div>
                                        @endif


                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Description</label>
                                            <textarea name="description"  id="description" rows="4" class="form-control summernote"
                                                placeholder="Enter description">{{ $boutique->description }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Product Highlight</label>
                                            <textarea name="highlight"  id="description" rows="2" class="form-control summernote"
                                                placeholder="Enter description">{{ $boutique->highlight }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Choose Boutique Color</label>
                                            <select class="form-control" name="colors" >
                                                @if($boutique->color)
                                                <option value="{{$boutique->color_id}}">{{$boutique->color->name}}</option>
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

                                        <div class="btn btn-danger prev-btn" current="attribute-tab" prev-tab= "boutique-tab">Previous</div>
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
                                                        <img src="{{ asset($boutique->image) }}" class="img-fluid" alt="">
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
                                        <div class="col-4 text-right">
                                                    <div style="max-width: 100px; max-height: 100px;overflow:hidden; margin-left: auto">
                                                        <img src="{{ asset($boutique->image) }}" class="img-fluid" alt="">
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


    var sizes = {!! json_encode($boutique->sizes) !!};
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
       $('.brands').hide();
       $('option[cate_id="'+id+'"]').show();

   })

    </script>


<script>

$('input[name="sizes[]"]').on('change', function(e) {
        var id = $(this).val();
        if($(this).prop("checked")) {
            $('.product-sku').hide()
            $('.togglesize'+id).show();
            $('input[name="prices['+id+']"]').prop('disabled', false);
            $('input[name="stocks['+id+']"]').prop('disabled', false);
            $('input[name="sku['+id+']"]').prop('disabled', false);

        } else {
            $('.product-sku').show()
            $('.togglesize'+id).hide();
            $('input[name="prices['+id+']"]').prop('disabled', true);
            $('input[name="stocks['+id+']"]').prop('disabled', true); 
            $('input[name="sku['+id+']"]').prop('disabled', true);
          
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