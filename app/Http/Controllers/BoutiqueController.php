<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use App\Models\Color;
use App\Models\Size;
use App\Models\Attribute;
use App\Models\Specification;
use App\Models\Image_product;
use App\Models\Order;
use App\Models\Designer;


class BoutiqueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->is_admin == 1){
            $boutiques = Product::with('colors','sizes','designer')->whereNotNull('designer_id')->orderBy('created_at', 'DESC')->paginate(20);
       
        }else{
            $boutiques = Product::with('colors','sizes','designer')->where('seller_id',auth()->user()->id)->whereNotNull('designer_id')->orderBy('created_at', 'DESC')->paginate(20);
       
        }
        return view('admin.boutique.index', compact('boutiques'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $boutique = Category::where('slug','boutique')->first();
        $categories = Category::where('parent_id',$boutique->id)
        ->with('childrenCategories')
        ->get();      
        $sizes = Size::all();
        $colors = Color::all();

        if(auth()->user()->is_admin==1){
            $designers = Designer::all();

        }else{
            $designers = Designer::where('seller_id',auth()->user()->id)->get();

        }

        $attributes = Attribute::all();
        
        return view('admin.boutique.create',compact('categories','sizes','designers','colors','attributes'));
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

        // dd($sizes);

    


        $this->validate($request, [
            'name' => 'required',
            'image' => 'required',
            'description' => 'required',
            'category' =>'required',
            'discount' =>  'required',
           'stock' => 'required',
           'price' => 'required',
            'colors'=>'required',
            'designer'=>'required'
           
            
        ]);
        if($request->product_sku){
            $sku= $request->product_sku;
        }else{
            $sku=' ';
        }

        // if product is added by admin, select selected designer id as seller id else use current user id as seller id
        if(auth()->user()->is_admin==1){
            $designer_id = $request->designer;
            $designer = Designer::findorfail($designer_id);
            $seller_id = $designer->seller_id;
        }else{
            $seller_id = auth()->user()->id;
        }

        if($request->highlight){
            $highlight= $request->highlight;
        }else{
            $highlight= '';
        }
        $detail =  strip_tags($request->description);
        $boutique = Product::create([        
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'image' => 'image.jpg',
            'description' => $detail,
            'category_id' => $request->category,
            'designer_id' => $request->designer,
            'price' => $request->price,
            'discount' => $request->discount,
            'rating' => $request->rating,
            'stock' => $request->stock,
            'size' =>$request->sizes,
            'color_id'=>$request->colors,
            'published_at' => Carbon::now(),
            'brand_id' =>0,
            'sku' => $sku,
            'seller_id'=> $seller_id,
            'highlight'=>$highlight
  
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
        $boutique->sizes()->attach($sizes);

       

        if($request->hasFile('image')){
            $image = $request->image;
            $image_new_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('storage/product/', $image_new_name);

            $boutique->image = '/storage/product/' . $image_new_name;
           
        }

        if($request->hasFile('feature_image')){
            $path = array();
            foreach ($request->feature_image as $image) {
                $image_new_name = time() . rand(1, 9999) . '.' . $image->getClientOriginalExtension();
                $image->move('storage/product/', $image_new_name);
                $path[] = '/storage/product/' . $image_new_name;
            }
            $img_path = implode(",", $path);
            $boutique->feature_images = $img_path;
        }

        

        $boutique->save();
        
        Session::flash('success', 'Product added successfully');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        if(auth()->user()->is_admin==1){
            $boutique = Product::with('category','sizes','designer')->findorfail($id);

        }else{
            $boutique = Product::with('category','sizes','designer')->where('seller_id', auth()->user()->id)->findorfail($id);

        }
        
        $colors = Color::all();
        $sizes = Size::all();

        if(auth()->user()->is_admin==1){
            $designers = Designer::all();

        }else{
            $designers = Designer::where('seller_id',auth()->user()->id)->get();

        }

        $categories = Category::all();
        $attributes = Attribute::all();
        
        return view('admin.boutique.edit', compact(['boutique','designers','categories','colors','sizes','attributes']));
    

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $boutique)
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
            'discount' =>  'required',
           'stock' => 'required',
           
           'colors'=>'required',
           'designer' =>'required',
           'price' => 'required',
           
        ]);

        if(auth()->user()->is_admin==1){
            $designer_id = $request->designer;
            $designer = Designer::findorfail($designer_id);
            $seller_id = $designer->seller_id;
        }else{
            $seller_id = auth()->user()->id;
        }
       
       
        if($request->product_sku){
            $boutique->sku = $request->product_sku;
           }
           if($request->highlight){
            $boutique->highlight = $request->highlight;
           }
    

       
       
            $boutique->name = $request->name;
            $boutique->designer_id = $request->designer;
            $boutique->seller_id = $seller_id;
            $boutique->slug = Str::slug($request->name);
            $boutique->description = $request->description;
           
           
            $boutique->category_id = $request->category;
            $boutique->stock = $request->stock;
            $boutique->discount = $request->discount;
            // update the product_size pivot table
            $boutique->sizes()->sync($sizes);
            $boutique->color_id = $request->colors;
            $boutique->price = $request->price;
          
          

            if($request->hasFile('image')){
                $image = $request->image;
                $image_new_name = time() . '.' . $image->getClientOriginalExtension();
                $image->move('storage/product/', $image_new_name);
                $boutique->image = '/storage/product/' . $image_new_name;
                $boutique->save();
            }

            if($request->hasFile('feature_image')){
                $path = array();
                foreach ($request->feature_image as $image) {
                    $image_new_name = time() . rand(1, 9999) . '.' . $image->getClientOriginalExtension();
                    $image->move('storage/product/', $image_new_name);
                    $path[] = '/storage/product/' . $image_new_name;
                }
                $img_path = implode(",", $path);
                $boutique->feature_images = $img_path;
            }


           

            $boutique->save();

            Session::flash('success', 'Boutique updated successfully');
            return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $boutique)
    {
        if($boutique){
            if(file_exists(public_path($boutique->image))){
                unlink(public_path($boutique->image));
            }
            $boutique->delete();
            Session::flash('$boutique deleted successfully');
        }
        return redirect()->back();
    }

    // fetch products to boutique page
public function boutique(){
    $boutique = Category::where('slug','boutique')->first();
    $categories = Category::where('parent_id',$boutique->id)->with('childrenCategories')->get();
    $boutique_products = findProducts($boutique->id);
    $colors= Color::all();
    $designers = Designer::all();
    return json_encode(array('boutique_products'=>$boutique_products,'categories'=>$categories,'colors'=>$colors,'designers'=>$designers,'boutique'=>$boutique));
 
}

// boutique page sidebar filtering according to color,price,category

public function boutiquefilter(Request $request){
   
      if($request->minprice && $request->maxprice){
            $minprice = min($request->minprice);
            $maxprice= max($request->maxprice);
        }else{
            $minprice= NULL;
            $maxprice= NULL;
        }


        if($request->cate && $request->color && $request->maxprice) {
            $product = Product::with('category','colors','color','sizes','designer')->whereNotNull('designer_id')->whereIn('color_id',$request->color)->whereIn('category_id',$request->cate)->where('price','>=',$minprice)->where('price','<=',$maxprice)->get();
            
        }elseif($request->cate && $request->color){
            $product = Product::with('category','colors','color','sizes','designer')->whereNotNull('designer_id')->whereIn('color_id',$request->color)->whereIn('category_id',$request->cate)->get();


        }elseif($request->cate && $request->maxprice){
            $product = Product::with('category','colors','color','sizes','designer')->whereNotNull('designer_id')->whereIn('category_id',$request->cate)->where('price','>=',$minprice)->where('price','<=',$maxprice)->get();


        }elseif( $request->color && $request->maxprice){
            $product = Product::with('category','colors','color','sizes','designer')->whereNotNull('designer_id')->whereIn('color_id',$request->color)->where('price','>=',$minprice)->where('price','<=',$maxprice)->get();


        }elseif($request->cate){
            $product = Product::with('category','colors','color','sizes','designer')->whereNotNull('designer_id')->whereIn('category_id',$request->cate)->get();

            
        }elseif($request->color){
            $product = Product::with('category','colors','color','sizes','designer')->whereNotNull('designer_id')->whereIn('color_id',$request->color)->get();

        }elseif($request->maxprice){
            $product = Product::with('category','colors','color','sizes','designer')->whereNotNull('designer_id')->where('price','>=',$minprice)->where('price','<=',$maxprice)->get();

        }else{
            $product = Product::with('category','colors','color','sizes','designer')->whereNotNull('designer_id')->get();
        }

        return $product;


}



// fetch products into designer page by designer's slug

public function designerdress($slug){
    $designer = Designer::where('slug',$slug)->first();
    $boutique = Category::where('slug','boutique')->first();
    $categories = Category::where('parent_id',$boutique->id)
        ->with('childrenCategories')
        ->get();

    $dress=  Product::with('category','designer')->where('designer_id',$designer->id)->get();
    $colors= Color::all();
   return json_encode(array('dress'=>$dress,'colors'=>$colors,'categories'=>$categories,'designer'=>$designer));
}


// designer page filter by category and designers slug
public function catdesign(Request $request){
    $design =Product::with('category')->where('category_id',$request->category_id)->where('designer_id',$request->designer_id)->get();
    return $design;
}

// designer page filter by color,category,designer_slug
public function designcolor(Request $request){
    if($request->category_id) {
        $designcolor =Product::with('category')->where('category_id',$request->category_id)->where('color_id',$request->color_id)->where('designer_id',$request->designer_id)->get();

    } else {
        $designcolor =Product::with('category')->where('color_id',$request->color_id)->where('designer_id',$request->designer_id)->get();

    }
    return $designcolor;
}


public function boutiquecolor(Request $request){
    if($request->category_id) {
        $boutiquecolor =Product::with('category')->where('category_id',$request->category_id)->where('color_id',$request->color_id)->whereNotNull('designer_id')->get();

    } else {
        $boutiquecolor = Product::with('category')->where('color_id',$request->color_id)->whereNotNull('designer_id')->get();
    }
    return $boutiquecolor;
}

    public function cateboutique(Request $request){
        $boutique = Product::with('category')->where('category_id',$request->category_id)->whereNotNull('designer_id')->get();
        return $boutique;

    }


    // designer page sidebar filter by color,category,price
    public function designerfetch(Request $request){

        if($request->minprice && $request->maxprice){
            $minprice = min($request->minprice);
            $maxprice = max($request->maxprice);  
        }else{
            $minprice= NULL;
            $maxprice= NULL;
        }
        $designer = Designer::where('slug',$request->designer)->first();

        if($request->cate && $request->color && $request->maxprice) {
            $product = Product::with('category','colors','color','sizes','designer')->where('designer_id',$designer->id)->whereIn('color_id',$request->color)->whereIn('category_id',$request->cate)->where('price','>=',$minprice)->where('price','<=',$maxprice)->get();
            
        }elseif($request->cate && $request->color){
            $product = Product::with('category','colors','color','sizes','designer')->where('designer_id',$designer->id)->whereIn('color_id',$request->color)->whereIn('category_id',$request->cate)->get();


        }elseif($request->cate&& $request->maxprice){
            $product = Product::with('category','colors','color','sizes','designer')->where('designer_id',$designer->id)->whereIn('category_id',$request->cate)->where('price','>=',$minprice)->where('price','<=',$maxprice)->get();


        }elseif( $request->color && $request->maxprice){
            $product = Product::with('category','colors','color','sizes','designer')->where('designer_id',$designer->id)->whereIn('color_id',$request->color)->where('price','>=',$minprice)->where('price','<=',$maxprice)->get();


        }elseif($request->cate){
            $product = Product::with('category','colors','color','sizes','designer')->where('designer_id',$designer->id)->whereIn('category_id',$request->cate)->get();

            
        }elseif($request->color){
            $product = Product::with('category','colors','color','sizes','designer')->where('designer_id',$designer->id)->whereIn('color_id',$request->color)->get();

        }elseif($request->maxprice){
            $product = Product::with('category','colors','color','sizes','designer')->where('designer_id',$designer->id)->where('price','>=',$minprice)->where('price','<=',$maxprice)->get();

        }else{
            $product = Product::with('category','colors','color','sizes','designer')->where('designer_id',$designer->id)->get();
        }

        return $product;

    }

}
