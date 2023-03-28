<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Session;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Designer;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use App\Models\Color;
use App\Models\Size;
use App\Models\Attribute;
use App\Models\Specification;
use App\Models\Image_product;
use App\Models\Order;
use App\Models\Addtocart;
use App\Models\User;
use App\Models\Transaction;
use DB;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        if(auth()->user()->is_admin == 1){
            $products = Product::with('colors','sizes','user')->whereNull('designer_id')->orderBy('created_at', 'DESC')->paginate(20);
        } else {
            $products = Product::with('colors','sizes','user')->whereNull('designer_id')->where('seller_id', auth()->user()->id)->orderBy('created_at', 'DESC')->paginate(20);
        }
        return view('admin.product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('parent_id',0)
        ->with('childrenCategories')
        ->get(); 
        if(auth()->user()->is_admin == 1){
            $brands = Brand::all();
        }else{
            $brands = Brand::where('name',auth()->user()->name)->get();
        }
        $brand_seller = User::where('role','brand_seller')->get();
        $sizes = Size::all();
        $colors = Color::all();
        $attributes = Attribute::all();
        return view('admin.product.create',compact('categories','sizes','colors','brands','attributes','brand_seller'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // build attatch method array to insert into db 
        // $sizes = [{size_id}=>["price"=>20, "stock"=>4, "sku"=>]]
        $sizes = Array();
        if($request->prices) {
            foreach($request->prices as $key => $price) {
                $sizes[$key] = Array('price'=>$price);

                foreach($request->stocks as $key2 => $stock) {
                    if($key==$key2) {
                        $newstock = Array('stock'=>$stock);
                        
                        $sizes[$key] = array_merge($sizes[$key], $newstock);
                    }
                }
                foreach ($request->sku as $key3 => $sku) {
                    if($key==$key3){
                        $newsku = Array('sku'=>$sku);
                        $sizes[$key] = array_merge($sizes[$key],$newsku);
                    }
                 }

            }
        }

        

        $this->validate($request, [
            'name' => 'required',
            'image' => 'required',
            'description' => 'required',
            'category' =>'required',
           'stock' => 'required',
           'price' => 'required',
            'colors'=>'required',  
        ]);

        if($request->brand){
            $brand = $request->brand;
        }else{
            $brand = 0;
        }

        if($request->product_sku){
            $sku= $request->product_sku;
        }else{
            $sku=' ';
        }
        if($request->discount){
            $discount= $request->discount;
        }else{
            $discount= 0;
        }
        if($request->highlight){
            $highlight= $request->highlight;
        }else{
            $highlight= '';
        }

        if($request->feature){
            $feature = $request->feature;
        }else{
            $feature = '';
        }

        if($request->brand_seller) {
            $seller = $request->brand_seller;
        } else {
            $seller = auth()->user()->id;
        }

        $detail =  strip_tags($request->description);
        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'image' => 'image.jpg',
            'description' => $detail,
            'category_id' => $request->category,
            'sku'=> $sku,
            'brand_id' => $brand,
            'price' => $request->price,
            'user_id' => auth()->user()->id,
            'discount' => $discount,
            'highlight' => $highlight,
            'rating' => $request->rating,
            'stock' => $request->stock,
            'size' =>$request->sizes,
            'color_id'=>$request->colors,
            'published_at' => Carbon::now(),
            'seller_id'=> $seller,
            'feature' => $feature
        ]);

        // add additional product detail to specifications table
        foreach($request->attr as $key => $value) {
            if($value) {
                $specification = Specification::create([
                    'product_id'=> $product->id,
                    'attribute_name'=>$key,
                    'value' => $value,
                ]);
            }
         }

        // add sizes to product_size pivot table
        $product->sizes()->attach($sizes); 
        if($request->hasFile('image')){
            $image = $request->image;
            $image_new_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('storage/product/', $image_new_name);
            $product->image = '/storage/product/' . $image_new_name;
        }

        if($request->hasFile('feature_image')){
            $path = array();
            foreach ($request->feature_image as $image) {
                $image_new_name = time() . rand(1, 9999) . '.' . $image->getClientOriginalExtension();
                $image->move('storage/product/', $image_new_name);
                $path[] = '/storage/product/' . $image_new_name;
            }
            $img_path = implode(",", $path);
            $product->feature_images = $img_path;
        }
        $product->save();        
        Session::flash('success', 'Product added successfully');
        return redirect()->back();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $product= Product::with('category','sizes','brand','color','specifications')->where('id',$product->id)->first();
       
        return view('admin.product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        if(auth()->user()->is_admin == 1){
        $product = Product::with('category','sizes')->findorfail($product->id);
        }else{
            $product = Product::with('category','sizes')->where('seller_id', auth()->user()->id)->findorfail($product->id);
        }

        if(auth()->user()->is_admin == 1){
            $brands = Brand::all();
        }else{
            $brands = Brand::where('name',auth()->user()->name)->get();
        }
        $brand_seller = User::where('role','brand_seller')->get();

        $colors = Color::all();
        $sizes = Size::all();
        $categories = Category::all();
        $attributes = Attribute::all();
        return view('admin.product.edit', compact(['product','categories','colors','sizes','brands','brand_seller','attributes']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        // build attatch method array to insert into db 
        // $sizes = [{size_id}=>["price"=>20, "stock"=>4, "sku"=>]]
        $sizes = Array();
        if($request->prices){
            foreach($request->prices as $key => $price) {
                $sizes[$key] = Array('price'=>$price);    
                foreach($request->stocks as $key2 => $stock) {
                    if($key==$key2) {
                        $newstock = Array('stock'=>$stock);                      
                        $sizes[$key] = array_merge($sizes[$key], $newstock);
                    }
                }

                foreach ($request->sku as $key3 => $sku) {
                   if($key==$key3){
                       $newsku = Array('sku'=>$sku);
                       $sizes[$key] = array_merge($sizes[$key],$newsku);
                   }
                }
    
            }
        }


        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'category'  =>'required',
            
           'stock' => 'required',
           
           'colors'=>'required',
           'price' => 'required',
           
        ]);

       if($request->brand){
        $product->brand_id = $request->brand;
       }

       if($request->product_sku){
        $product->sku = $request->product_sku;
       }
       if($request->discount){
        $product->discount = $request->discount;
       }
       if($request->highlight){
        $product->highlight = $request->highlight;
       }

    if($request->brand_seller) {
        $seller = $request->brand_seller;
    } else {
        $seller = auth()->user()->id;
    }
    $product->seller_id = $seller;

    $detail =  $request->description;
    

        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->description = $detail;
        $product->category_id = $request->category;
        $product->stock = $request->stock;
        
        
        $product->sizes()->sync($sizes);
        $product->color_id = $request->colors;
        $product->price = $request->price;
        
        

        if($request->hasFile('image')){
            $image = $request->image;
            $image_new_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('storage/product/', $image_new_name);
            $product->image = '/storage/product/' . $image_new_name;
            $product->save();
        }

        if($request->hasFile('feature_image')){
            $path = array();
            foreach ($request->feature_image as $image) {
                $image_new_name = time() . rand(1, 9999) . '.' . $image->getClientOriginalExtension();
                $image->move('storage/product/', $image_new_name);
                $path[] = '/storage/product/' . $image_new_name;
            }
            $img_path = implode(",", $path);
            $product->feature_images = $img_path;
        }
        $product->save();

        Session::flash('success', 'Product updated successfully');
        return redirect()->back();
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( Product $product)
    {

        if($product){
            if(file_exists(public_path($product->image))){
                unlink(public_path($product->image));
            }
            $product->visible=false;
            $product->save();
            Session::flash('product deleted successfully');
        }

        return redirect()->back();
    }

    //Product Detail Page
    public function detail($id){
        $detail= Product::with('category','colors','color','sizes','brand','specifications','designer')->where('id',$id)->first();
    
        //create breadcrumb
        //look into helper.php
        $breadcrumb=[];
        findParents($detail->category->parent_id,$breadcrumb,$detail->category_id);    

        //related products
        $related = Product::with('category','colors','color','sizes','brand','specifications','designer')->where('category_id',$detail->category_id)->where('id','!=',$id)->get();

        //check if product is brand product or designer product
        if($detail->brand_id && $detail->brand_id > 0) {
            $branded = Product::with('category','colors','color','sizes','brand','specifications')->where('brand_id',$detail->brand_id)->whereNotNull('brand_id')->where('id','!=',$id)->where('brand_id','!=',0)->get();
        } else {
            $branded = Product::with('category','colors','color','sizes','specifications','designer')->where('designer_id',$detail->designer_id)->whereNotNull('designer_id')->where('id','!=',$id)->get();
        }
       
        // Designers Related Prodcuts
        $designers = Product::with('category','colors','color','sizes','specifications','designer')->where('designer_id',$detail->designer_id)->whereNotNull('designer_id')->where('id','!=',$id)->get();

        return json_encode(array('detail'=>$detail,'related'=>$related,'branded'=>$branded,'designers'=>$designers,'breadcrumb'=>$breadcrumb));
    }

    // for review page
    public function buyers($id)
    {
        //$product = Product::where('id',$id)->first();
        $buyer = Order::where('product_id', $id)->where('user_id',auth()->user()->id)->first();
        return $buyer;
    }
    

    // search page filter
    public function searchfilter(Request $request){
        $q = strtolower($request->searchquery);
        $splite = explode(' ',$q);
        $sizeofquery = sizeof($splite);
        // look into helper
        $product = doRepeated($request, $splite, $q);
        return $product;
    }


    public function singleProduct($id){
        $product = Product::findorfail($id);
        return $product;
    }
    
}
