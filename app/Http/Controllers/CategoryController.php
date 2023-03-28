<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Attribute;
use App\Models\Wishlist;

use Session;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::where('parent_id',0)
        ->with('childrenCategories')
        ->get();
         return view('admin.category.index', compact('categories'));
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
        $attributes = Attribute::all();
        return view('admin.category.create',compact('categories','attributes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {// validation
        $this->validate($request, [
            'name' => 'required',
            'parent_id'=>'required',
            'image' => 'required',
        ]);

        // if category has parent use parent category's slug in category slug else use category slug
        $pt = Category::where('id',$request->parent_id)->first();
        if ($pt){
            $parent_slug = $pt->slug;
            $slug = $parent_slug."-".Str::slug($request->name, '-');
        } else{
            $slug = Str::slug($request->name, '-');
        }
      

        $category = Category::create([
            'name' => $request->name,
            'parent_id'=>$request->parent_id,
            'image' => 'image.jpg',
            'slug' => $slug,
            
        ]);

        // add extra attribute field for category,this field will display while adding product
        $category->attribute()->attach($request->attr);

        if($request->hasFile('image')){
            $image = $request->image;
            $image_new_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('storage/category/', $image_new_name);
            $category->image = '/storage/category/' . $image_new_name;
            $category->save();
        
        }

        Session::flash('success', 'Category created successfully');

        return redirect()->back();


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $categories = Category::where('parent_id',0)
        ->with('childrenCategories')
        ->get();

        $category = Category::with('parent')->where('id',$category->id)->first();
        return view('admin.category.edit',compact('category','categories'));
        }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $this->validate($request, [
            'name'=>"required",
           'parent_id'=>"required"

        ]);

        $pt = Category::where('id',$request->parent_id)->first();
        if ($pt){
            $parent_slug = $pt->slug;
            $slug = $parent_slug."-".Str::slug($request->name, '-');
        }
        else{
            $slug = Str::slug($request->name, '-');
        }

        $category->name = $request->name;
        $category->slug= $slug;
       $category->parent_id= $request->parent_id;

        if($request->hasFile('image')){
            $image = $request->image;
            $image_new_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('storage/category/', $image_new_name);
            $category->image = '/storage/category/' . $image_new_name;
        }

        $category->save();
        Session::flash('success', 'Category updated successfully');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if($category){
                if(file_exists(public_path($category->image))){
                    unlink(public_path($category->image));
                }
            $category->delete();

            Session::flash('success', 'Category deleted successfully');
            return redirect()->route('category.index');
        }
    }

    // fetch first layer category to display in navbar
    public function category(){
        $categories = Category::where('parent_id',0)
        ->with('childrenCategories')
        ->get();
        return $categories;
    }

    // find product according to category id
    public function catproduct($id)
    {
        $products = Product::where('category_id',$id)->get();
        return $products; 
    }


    // Parent category page i.e. /men, /women
    public function catdetail($slug){
        $slug = Category::where('slug',$slug)->first();
        if(is_null($slug)){
            $categories= Category::where('parent_id','!=',0)->get();
            $brands = Brand::all();
            $breadcrumb = NULL;
        }else{
            $categories = Category::where('parent_id',$slug->id)
            ->with('childrenCategories')
            ->get();
            $brands = findBrands($slug->id);        
            $breadcrumb=[];
            findParents($slug->parent_id, $breadcrumb);
        }
        $colors= Color::all();
        return json_encode(array('categories'=>$categories, 'brands'=>$brands,'colors'=>$colors, 'breadcrumb'=>$breadcrumb,'currentCategory'=>$slug));
    }


    // category page sidebar filter
    public function filterFetch(Request $request) {

        $category = Category::where('slug', $request->cate)->first();

        if($request->minprice && $request->maxprice){
            $minprice = min($request->minprice);
            $maxprice= max($request->maxprice);
        }else{
            $minprice= NULL;
            $maxprice= NULL;
        }
       
        // look into app/Http/helper.php
        $products = fetchProducts($request->brand, $category->id, $request->color, $minprice,$maxprice);
        return $products;
  
    }



    // brand page sidebar filter
    public function brandfilter(Request $request){
        //return $request;
        $brand = Brand::where('slug', $request->brand)->first();
        $color= Color::where('slug',$request->color)->first();
        if($request->brand && $request->color && $request->min && $request->max) {
            $pro = Product::where('brand_id',$brand->id)->where('color_id',$color->id)->where('price','>',$request->min)->where('price','<',$request->max)->get();
        } elseif($request->brand && $request->max) {
            $pro = Product::where('brand_id',$brand->id)->where('price','>',$request->min)->where('price','<',$request->max)->get();
        } else {
            $pro = Product::where('brand_id',$brand->id)->where('color_id',$color->id)->get();
        }             
        
        return $pro;
    }





    // not in use
    public function branddetail($slug) {
        $slug = Brand::where('slug',$slug)->first();
        $brand = Product::where('brand_id', $slug->id)->where('category_id',$slug->category_id)->get();
        return $brand;
    }

    


    // find products by color and category
   public function colordetail($slug, $category_slug){
       $slug= Color::where('slug',$slug)->first();
        $category = Category::where('slug', $category_slug)->first();
        // look into /helper.php
       $products = findColors($slug->id, $category->id);
       return $products;
   }


    
}
