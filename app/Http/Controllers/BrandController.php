<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Category;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Str;
use Session;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands=Brand::all();
        return view('admin.brand.index',compact('brands'));
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
        return view('admin.brand.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'image' => 'required',
          
        ]);

        if($request->hasFile('image')){
            $image = $request->image;
            $image_new_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('storage/brand/', $image_new_name);
            $brand_image = '/storage/brand/' . $image_new_name;        
        }
            $brand = Brand::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name, '-'), 
                'category_id' => 0,
                'image' => $brand_image,
            ]);

        Session::flash('success', 'Brand created successfully');

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {

        $categories = Category::where('parent_id',0)
        ->with('childrenCategories')
        ->get();
        return view('admin.brand.edit', compact('brand','categories'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand $brand)
    {
        $this->validate($request, [
            'name' => "required",
            ]);
    
           
           $user = User::where('name',$brand->name)->first();
           if(!is_null($user)){
               $user->name = $request->name;
               $user->save();
           }

           if($request->hasFile('image')){
            $image = $request->image;
            $image_new_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('storage/brand/', $image_new_name);
            $brand->image = '/storage/brand/' . $image_new_name;
        }
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name, '-');
            $brand->save();
    
            Session::flash('success', 'Brand updated successfully');
            return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        if($brand){
          
            $products = Product::where('brand_id',$brand->id)->get();
            $users = User::where('name',$brand->name)->first();
            // delete user related to brand
            if(!is_null($users)){
                $users->delete();
            }
            // delete products related to brand
            foreach($products as $product){
                $product->visible=false;

                $product->save();
            
            }
            $brand->delete();

            Session::flash('success', 'Brand deleted successfully');
            return redirect()->route('brand.index');
        }
    }

    // brand page sidebar filter

    public function brandfetch(Request $request){
        if($request->maxprice && $request->minprice){
            $minprice=min($request->minprice);
            $maxprice= max($request->maxprice);
        }else{
            $minprice=NULL;
            $maxprice= NULL;
        }
        if($request->color && $request->maxprice){
            $products = Product::with('category','colors','color','sizes','brand')->whereIn('brand_id', $request->brands)->whereIn('color_id',$request->color)->where('price','>=',$minprice)->where('price','<=',$maxprice)->get();

        }elseif($request->color){
            $products = Product::with('category','colors','color','sizes','brand')->whereIn('brand_id', $request->brands)->whereIn('color_id',$request->color)->get(); 
            
        }elseif($request->maxprice){
            $products = Product::with('category','colors','color','sizes','brand')->whereIn('brand_id', $request->brands)->where('price','>=',$minprice)->where('price','<=',$maxprice)->get();


        }elseif($request->brands){
            $products = Product::with('category','colors','color','sizes','brand')->whereIn('brand_id', $request->brands)->get();
        }else{
            $products= [];
        }


        return $products;
    
    }



}
