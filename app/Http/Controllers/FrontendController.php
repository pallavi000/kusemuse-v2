<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Banner;
use App\Models\Addtocart;
use Illuminate\Http\Request;
use App\Models\Color;
use App\Models\CategoryBanner;
use App\Models\Featureblock;


class FrontendController extends Controller
{

    // home page 
    public function product()
    {
        
        // not in use
        $banner_category= Category::where('parent_id',0)->get();
        $ids = [];
        foreach($banner_category as $category){
            array_push($ids, $category->id);
        }
        //$categories = Category::whereIn('parent_id',$ids)->inRandomOrder()->limit(6)->get();


        $women_category=Category::where('slug','women')->first();
        $women_products = findProducts($women_category->id);

        $men_category= Category::where('slug','men')->first();
        $men_products = findProducts($men_category->id);



        $brands = Brand::inRandomOrder()->limit(10)->get();
        $banner = Banner::whereNull('category_id')->inRandomOrder()->limit(3)->get();

        $featureblock = Featureblock::orderBy('created_at','DESC')->limit(4)->get();
        $categoryblock = CategoryBanner::orderBy('created_at','DESC')->limit(6)->get();
        
      

        
        // $category= Category::where('slug', $request->slug)->first();
        // $women_brands = Brand::where('category_id',$category->id)->get();


        $boutique_category = Category::where('slug','boutique')->first();

        $boutique_product = findProducts($boutique_category->id);
        $boutique_banner = Banner::where('category_id',$boutique_category->id)->first();
       
        return json_encode(array( 'boutique_product'=>$boutique_product,'banner'=>$banner,'men_products'=>$men_products,'women_products'=>$women_products,'boutique_banner'=>$boutique_banner,'banner_category'=>$banner_category,'brands'=>$brands,'featureblock'=>$featureblock,'categoryblock'=>$categoryblock));
    }
    
    public function category(){
        $categories = Category::where('parent_id',0)
        ->with('childrenCategories')
        ->get();
        return $categories;
    }

    public function categorypage($slug){
        $category = Category::where('slug',$slug)->first();
        if(!is_null($category)){
            $category_product = findProducts($category->id);
            $category_banner = Banner::where('category_id',$category->id)->first();
            $category_brand = findBrands($category->id);
    
            $categoryblock = CategoryBanner::where('category_id',$category->id)->orderBy('created_at','DESC')->limit(6)->get();
    
            
            $categories = Category::where('parent_id',$category->id)->with('childrenCategories')->get();
    
            // find products of current category and child category
            // look into helper.php
            $feature = findFeature($category->id);
    
            return json_encode(array('category_product'=>$category_product,'feature'=>$feature,'category_banner'=>$category_banner,'category_brand'=>$category_brand,'categories'=>$categories,'categoryblock'=>$categoryblock));

        } else {
            return response()->json([' category not found'], 404);
        }
       
    }

    public function checkguest($id){
        $guest_carts = Addtocart::where('user_id',$id)->get();
        foreach ($guest_carts as $cart) {
            $cart->user_id = auth()->user()->id;
            $cart->save();            
        }
        return auth()->user();
    }

    public function check(){
        return auth()->user();
    }

 
    // fetch products into BrandPage by brand_slug
    public function brandpage($slug){
        $brand = Brand::where('slug',$slug)->first();
        $brand_product = array();
        if(!is_null($brand)) {
            $brand_product = Product::where('brand_id',$brand->id)->get(); 
        }
        $brands = Brand::all();
        $colors= Color::all();
        return json_encode(array('brand_product'=>$brand_product,'colors'=>$colors,'brands'=>$brands,'brand'=>$brand));
    }
}
