<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Color;
use Session;
use DB;
use Illuminate\Support\Str;


// find products of category nd its child categories
function findChildProducts($cat_id, &$products) {
    $cat = Category::where('parent_id',$cat_id)->get();
    foreach ($cat as $key) {
        $pro = Product::with('brand','category','colors','color','sizes','designer')->where('category_id', $key->id)->get();
        $products = array_merge($products, $pro->toArray());
        findChildProducts($key->id, $products);
    }
}
function findProducts($cat_id) {
    $products = array();
    $pro = Product::with('brand','category','colors','color','sizes','designer')->where('category_id', $cat_id)->get();
    $products = array_merge($pro->toArray(), $products);
    findChildProducts($cat_id, $products);
    return $products;
}


// find feature products from category and its child categories
function findFeatureChild($cat_id, &$products) {
    $cat = Category::where('parent_id',$cat_id)->get();
    foreach ($cat as $key) {
        $pro = Product::with('brand','category','colors','color','sizes')->where('category_id', $key->id)->where('feature','feature')->get();
        $products = array_merge($products, $pro->toArray());
        findFeatureChild($key->id, $products);
    }
}
function findFeature($cat_id) {
    $products = array();
    $pro = Product::with('brand','category','colors','color','sizes')->where('category_id', $cat_id)->where('feature','feature')->get();
    $products = array_merge($pro->toArray(), $products);
    findFeatureChild($cat_id, $products);
    return $products;
}




// multiple search match
function searchmatch($data,$splite){
    $count = 0;
    foreach($splite as $lite){
        $check =  strpos($data,$lite);
        if($check===false) {  
        } else {
            $count += 1;
        }
    }
    return $count;
}

// search filter
function doRepeated($request, $splite, $q) {
    $products = [];
    if($request->minprice && $request->maxprice){
        $minprice = min($request->minprice);
        $maxprice= max($request->maxprice);
    }else{
        $minprice= NULL;
        $maxprice= NULL;
    }
    foreach($splite as $key => $lite) {
        if($request->cate && $request->color && $request->maxprice && $request->brand) {
            $product = Product::with('category','colors','color','sizes','brand','specifications','designer')->whereIn('color_id',$request->color)->whereIn('category_id',$request->cate)->where('price','>=',$minprice)->where('price','<=',$maxprice)->where('name','like','%'.$lite.'%')->orWhere('description','like','%'.$lite.'%')->whereIn('brand_id',$request->brand);
            $product = $product->get();
 
            
        }elseif($request->cate && $request->color && $request->maxprice){
            $product = Product::with('category','colors','color','sizes','brand','specifications','designer')->whereIn('color_id',$request->color)->whereIn('category_id',$request->cate)->where('price','>=',$minprice)->where('price','<=',$maxprice)->where('name','like','%'.$lite.'%')->orWhere('description','like','%'.$lite.'%');
            $product = $product->get();
 
        }
        elseif($request->cate && $request->color && $request->brand){
            $product = Product::with('category','colors','color','sizes','brand','specifications','designer')->whereIn('color_id',$request->color)->whereIn('category_id',$request->cate)->where('name','like','%'.$lite.'%')->orWhere('description','like','%'.$lite.'%')->whereIn('brand_id',$request->brand);
            $product = $product->get();

        }
        elseif($request->cate && $request->color ){
            $product = Product::with('category','colors','color','sizes','brand','specifications','designer')->whereIn('color_id',$request->color)->whereIn('category_id',$request->cate)->where('name','like','%'.$lite.'%')->orWhere('description','like','%'.$lite.'%');
            $product = $product->get();
        }elseif($request->cate && $request->maxprice && $request->brand){
            $product = Product::with('category','colors','color','sizes','brand','specifications','designer')->whereIn('category_id',$request->cate)->where('price','>=',$minprice)->where('price','<=',$maxprice)->where('name','like','%'.$lite.'%')->orWhere('description','like','%'.$lite.'%')->whereIn('brand_id',$request->brand);
            $product = $product->get();
            
        }elseif($request->cate && $request->maxprice){
            $product = Product::with('category','colors','color','sizes','brand','specifications','designer')->whereIn('category_id',$request->cate)->where('price','>=',$minprice)->where('price','<=',$maxprice)->where('name','like','%'.$lite.'%')->orWhere('description','like','%'.$lite.'%');
            $product = $product->get();

        }elseif( $request->color && $request->maxprice && $request->brand){
            $product = Product::with('category','colors','color','sizes','brand','specifications','designer')->whereIn('color_id',$request->color)->where('price','>=',$minprice)->where('price','<=',$maxprice)->where('name','like','%'.$lite.'%')->orWhere('description','like','%'.$lite.'%')->whereIn('brand_id',$request->brand);
            $product = $product->get();

        }elseif($request->color && $request->maxprice){
            $product = Product::with('category','colors','color','sizes','brand','specifications','designer')->whereIn('color_id',$request->color)->where('price','>=',$minprice)->where('price','<=',$maxprice)->where('name','like','%'.$lite.'%')->orWhere('description','like','%'.$lite.'%');
            $product = $product->get();

        }elseif($request->cate && $request->brand){
            $product = Product::with('category','colors','color','sizes','brand','specifications','designer')->whereIn('category_id',$request->cate)->where('name','like','%'.$lite.'%')->orWhere('description','like','%'.$lite.'%')->whereIn('brand_id',$request->brand);
            $product = $product->get();

        }elseif($request->cate){
            $product = Product::with('category','colors','color','sizes','brand','specifications','designer')->whereIn('category_id',$request->cate)->where('name','like','%'.$lite.'%')->orWhere('description','like','%'.$lite.'%');
            $product = $product->get();

        }elseif($request->color && $request->brand){
            $product = Product::with('category','colors','color','sizes','brand','specifications','designer')->whereIn('color_id',$request->color)->where('name','like','%'.$lite.'%')->orWhere('description','like','%'.$lite.'%')->whereIn('brand_id',$request->brand);
            $product = $product->get();

        }elseif($request->color){
            $product = Product::with('category','colors','color','sizes','brand','specifications','designer')->whereIn('color_id',$request->color)->where('name','like','%'.$lite.'%')->orWhere('description','like','%'.$lite.'%'); 
            $product = $product->get();

        }elseif($request->maxprice && $request->brand){
            $product = Product::with('category','colors','color','sizes','brand','specifications','designer')->where('price','>=',$minprice)->where('price','<=',$maxprice)->where('name','like','%'.$lite.'%')->orWhere('description','like','%'.$lite.'%')->whereIn('brand_id',$request->brand);
            $product = $product->get();

        }elseif($request->maxprice){
            $product = Product::with('category','colors','color','sizes','brand','specifications','designer')->where('price','>=',$minprice)->where('price','<=',$maxprice)->where('name','like','%'.$lite.'%')->orWhere('description','like','%'.$lite.'%');
            $product = $product->get();

        }elseif($request->brand){
            $product = Product::with('category','colors','color','sizes','brand','specifications','designer')->where('name','like','%'.$lite.'%')->orWhere('description','like','%'.$lite.'%')->whereIn('brand_id',$request->brand);
            $product = $product->get();

        }else{
            $product = Product::with('category','colors','color','sizes','brand','specifications','designer')->where('name','like','%'.$lite.'%')->orWhere('description','like','%'.$lite.'%');
            $product = $product->get();
            
        }
        $products = array_merge($products, $product->toArray());
        if(strlen($lite)<3){
            unset($splite[$key]);
            continue;
        }
        $removelist = array("in","it","a","the","of","for","I","you","he","me","us","they","she","to","but","that","this","those","then","what");
        $removecheck = array_search($lite,$removelist);
        if($removecheck){
            unset($splite[$key]);
        }
    }
    $sizeofquery = sizeof($splite);

    //nike tshirt
    foreach($products as $key => $pro){
        // Data Colletion
        $data = $pro['name']." ".$pro['description']." ".$pro['color']['name'];
        if($pro['brand']) {
            $data.=" ".$pro['brand']['name'];
        }      
        if($pro['category']){
            $data.=" ".$pro['category']['slug'];
        }
        if($pro['designer']){
            $data.=" ".$pro['designer']['name'];
        }
       foreach($pro['sizes'] as $sizes) {
           $data .= " ".$sizes['name'];
       }
       foreach($pro['specifications'] as $specific){
           $data .= " ".$specific['attribute_name']." ".$specific['value'];
       }
       $data = strtolower($data);

        //Filter Products
       if($sizeofquery < 3) {
           //all match
           $match = searchmatch($data,$splite);
           if($sizeofquery != $match) {
              unset($products[$key]);
           }

       }elseif($sizeofquery <4){
        //  two word
        $match= searchmatch($data,$splite);
        
        if($match < 2){
            unset($products[$key]);
        }

       }elseif($sizeofquery <5){
        //    3 words
        $match = searchmatch($data,$splite);
        if($match <3){
        unset($products[$key]);
        }


       }else{
        //    4 word
        $match = searchmatch($data,$splite);
        if($match <4){
        unset($products[$key]);
        }
    }
         
    }
    $products = array_map("unserialize", array_unique(array_map("serialize", $products)));
    $products = array_values($products);
    return $products;
}






function fetchChildProducts($brand_id, $cat_id,  $color_id,$minprice,$maxprice, &$products) {
    $cat = Category::where('parent_id', $cat_id)->get();

    foreach($cat as $key) {

        if($brand_id && $color_id && $maxprice){
            $pro = Product::with('category','colors','color','sizes','brand')->where('category_id',$key->id)->whereIn('brand_id',$brand_id)->whereIn('color_id',$color_id)->where('price','>=',$minprice)->where('price','<=',$maxprice)->get();
    
    
        }elseif($brand_id && $maxprice){
            $pro = Product::with('category','colors','color','sizes','brand')->where('category_id',$key->id)->whereIn('brand_id',$brand_id)->where('price','>=',$minprice)->where('price','<=',$maxprice)->get();
    
        }elseif( $color_id && $maxprice){
            $pro = Product::with('category','colors','color','sizes','brand')->where('category_id',$key->id)->whereIn('color_id',$color_id)->where('price','>=',$minprice)->where('price','<=',$maxprice)->get();
    
        }elseif( $maxprice){
            $pro = Product::with('category','colors','color','sizes','brand')->where('category_id',$key->id)->where('price','>=',$minprice)->where('price','<=',$maxprice)->get();
    
        }

        elseif($brand_id && $color_id) {
            $pro = Product::with('category','colors','color','sizes','brand')->where('category_id',$key->id)->whereIn('brand_id',$brand_id)->whereIn('color_id',$color_id)->get();
    
        }elseif($brand_id){
            $pro = Product::with('category','colors','color','sizes','brand')->where('category_id',$key->id)->whereIn('brand_id',$brand_id)->get();
    
        }elseif($color_id){
            $pro = Product::with('category','colors','color','sizes','brand')->where('category_id',$key->id)->whereIn('color_id',$color_id)->get();
    
        } else {
            //category only
            $pro = Product::with('category','colors','color','sizes','brand')->where('category_id',$key->id)->get();
        }

        
        $products = array_merge($pro->toArray(), $products);
        fetchChildProducts( $brand_id, $key->id, $color_id, $minprice,$maxprice, $products);
    }
}

function fetchProducts($brand_id, $cat_id, $color_id, $minprice, $maxprice) {
    $products =  array();

    if($brand_id && $color_id && $maxprice){
        $pro = Product::with('category','colors','color','sizes','brand')->where('category_id',$cat_id)->whereIn('brand_id',$brand_id)->whereIn('color_id',$color_id)->where('price','>=',$minprice)->where('price','<=',$maxprice)->get();


    }elseif($brand_id && $maxprice){
        $pro = Product::with('category','colors','color','sizes','brand')->where('category_id',$cat_id)->whereIn('brand_id',$brand_id)->where('price','>=',$minprice)->where('price','<=',$maxprice)->get();

    }elseif( $color_id && $maxprice){
        $pro = Product::with('category','colors','color','sizes','brand')->where('category_id',$cat_id)->whereIn('color_id',$color_id)->where('price','>=',$minprice)->where('price','<=',$maxprice)->get();

    }elseif($maxprice){
        $pro = Product::with('category','colors','color','sizes','brand')->where('category_id',$cat_id)->where('price','>=',$minprice)->where('price','<=',$maxprice)->get();

    }
    elseif($brand_id && $color_id) {
        $pro = Product::with('category','colors','color','sizes','brand')->where('category_id',$cat_id)->whereIn('brand_id',$brand_id)->whereIn('color_id',$color_id)->get();

    }elseif($brand_id){
        $pro = Product::with('category','colors','color','sizes','brand')->where('category_id',$cat_id)->whereIn('brand_id',$brand_id)->get();

    }elseif($color_id){
        $pro = Product::with('category','colors','color','sizes','brand')->where('category_id',$cat_id)->whereIn('color_id',$color_id)->get();

    } else {
        //category only
        $pro = Product::with('category','colors','color','sizes','brand')->where('category_id',$cat_id)->get();
    }
    
    $products = array_merge($pro->toArray(), $products);
    fetchChildProducts( $brand_id,$cat_id, $color_id,$minprice,$maxprice, $products);
    return $products;
}




function findChildColors($color_id, $category_id, &$products) {
    $cat = Category::where('parent_id', $category_id)->get();
    foreach ($cat as $key) {
        $pro = Product::where('color_id', $color_id)->where('category_id', $key->id)->get();
        $products = array_merge($products, $pro->toArray());
        findChildColors($color_id, $key->id, $products);
    }
}
function findColors($color_id, $category_id) {
    $products = array();
    $pro = Product::where('color_id', $color_id)->where('category_id', $category_id)->get();
    $products = array_merge($pro->toArray(), $products);
    findChildColors($color_id, $category_id, $products);
    return $products;
}


function array_unique_multidimensional($input)
{
    $serialized = array_map('serialize', $input);
    $unique = array_unique($serialized);
    return array_intersect_key($input, $unique);
}



function findChildBrands($cat_id, &$products) {
    $cat = Category::where('parent_id',$cat_id)->get();
    foreach ($cat as $key) {
        $pro = Product::with('brand')->where('category_id', $key->id)->inRandomOrder()->get();
        $products = array_merge($products, $pro->toArray());
        findChildProducts($key->id, $products);
    }
}

function findBrands($cat_id) {
    $products = array();
    $pro = Product::with('brand')->where('category_id', $cat_id)->inRandomOrder()->get();
    $products = array_merge($pro->toArray(), $products);

    findChildBrands($cat_id, $products);

    $brands = array();
    foreach($products as $product) {
        if(!empty($product['brand'])) {
            $brands[] = $product['brand'];
        }
        
    }
    $brands = array_map("unserialize", array_unique(array_map("serialize", $brands)));
    $brands = array_values($brands);
    return $brands;
}




// breadcrumb
function findParents($parent_id, &$arr, $id=false){
    if($id) {
        $category = Category::findorfail($id);
        array_push($arr,$category);
    }
    $parent = Category::where('id',$parent_id)->first();
    array_push($arr,$parent);
    if($parent->parent_id && $parent->parent_id!=0){
        findParents($parent->parent_id, $arr);
    }
}









?>