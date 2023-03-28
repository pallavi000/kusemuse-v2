<?php

namespace App\Http\Controllers;
use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use App\Models\CategoryBanner;

class CategoryBannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorybanners = CategoryBanner::with('category')->orderBy('created_at', 'DESC')->paginate(20);
        return view('admin.categorybanner.index',compact('categorybanners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('parent_id',0)->get();
        return view('admin.categorybanner.create',compact('categories'));
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
        $categorybanner = CategoryBanner::create([
            'title' => $request->title,
            'image' => 'image.jpg',
            'published_at' => Carbon::now(),
            'category_id' =>$request->category_id,
            'link'=>$request->link
  
        ]);

   
        if($request->hasFile('image')){
            $image = $request->image;
            $image_new_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('storage/categorybanner/', $image_new_name);
            $categorybanner->image = '/storage/categorybanner/' . $image_new_name;
           
        }


        
        $categorybanner->save();
        
        Session::flash('success', ' CategoryBanner added successfully');
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
    public function edit(CategoryBanner $categorybanner)
    {
        $categories = Category::where('parent_id',0)->get();
        return view('admin.categorybanner.edit', compact(['categorybanner','categories']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CategoryBanner $categorybanner)
    {
        $this->validate($request, [
            'title' => 'required',
        
           
        ]);
        $categorybanner->title = $request->title;
        $categorybanner->category_id = $request->category;
        $categorybanner->link = $request->link;
        if($request->hasFile('image')){
            $image = $request->image;
            $image_new_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('storage/categorybanner/', $image_new_name);
            $categorybanner->image = '/storage/categorybanner/' . $image_new_name;
        
        }
        $categorybanner->save();
        Session::flash('success', 'Brand updated successfully');
        return redirect()->back();
      

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CategoryBanner $categorybanner)
    {
        if($categorybanner){
            if(file_exists(public_path($categorybanner->image))){
                unlink(public_path($categorybanner->image));
            }
            $categorybanner->delete();
            Session::flash('success','categorybanner deleted successfully');
        }
        return redirect()->back();
    }
}
