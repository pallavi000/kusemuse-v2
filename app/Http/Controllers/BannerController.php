<?php

namespace App\Http\Controllers;
use Session;
use Carbon\Carbon;
use App\Models\Banner;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = Banner::with('category')->orderBy('created_at', 'DESC')->paginate(20);

        return view('admin.banner.index', compact('banners'));
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
        return view('admin.banner.create',compact('categories'));
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
            'title' => 'required',
            'image' => 'required',
            
            
        ]);
        $banner = Banner::create([
            'title' => $request->title,
            'image' => 'image.jpg',
            'published_at' => Carbon::now(),
            'category_id' =>$request->category_id,
            'link'=>$request->link
  
        ]);

   
        if($request->hasFile('image')){
            $image = $request->image;
            $image_new_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('storage/banner/', $image_new_name);
            $banner->image = '/storage/banner/' . $image_new_name;
           
        }


        
        $banner->save();
        
        Session::flash('success', ' Banner added successfully');
        return redirect()->back();

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function show(Banner $banner)
    {
        return view('admin.banner.show', compact('banner'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function edit(Banner $banner)
    {
        $categories = Category::where('parent_id',0)->get();
        return view('admin.banner.edit', compact(['banner','categories']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Banner $banner)
    {
        $this->validate($request, [
            'title' => 'required',
            'image' => 'required',
           
        ]);
        $banner->title = $request->title;
        $banner->image = $request->image;
        $product->category_id = $request->category;
        $banner->link = $request->link;
        if($request->hasFile('image')){
            $image = $request->image;
            $image_new_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('storage/banner/', $image_new_name);
            $banner->image = '/storage/banner/' . $image_new_name;
            $banner->save();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Banner $banner)
    {
        if($banner){
            if(file_exists(public_path($banner->image))){
                unlink(public_path($banner->image));
            }
            $banner->delete();
            Session::flash('banner deleted successfully');
        }

        return redirect()->back();
    }
    
}
