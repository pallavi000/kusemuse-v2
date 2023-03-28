<?php

namespace App\Http\Controllers;
use Session;
use Carbon\Carbon;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reviews = Review::with('product','user')->get();
        return view('admin.review.index',compact('reviews'));
    }


    public function update(Request $request, Review $review){

        $this->validate($request, [  
            'status' => 'required', 
        ]);

        $review->status = $request->status;
        $review->save();
    
        Session::flash('success', 'review updated successfully');
        return redirect()->back();

    }
    



    // create review
    public function comment(Request $request){
        $review_image = [];
        $files = $request->file('image');
        if($request->hasFile('image')){
           foreach($files as $file){
                $image = $file;
                $image_new_name = time() . '.' . $image->getClientOriginalExtension();
                $image->move('storage/review/', $image_new_name);
                $review = '/storage/review/' . $image_new_name;
                array_push($review_image,$review);
           }
        }
        $review_image = json_encode($review_image);
        Review::create([
            'comment' => $request->comment,
            'user_id' => auth()->user()->id,
            'product_id' => $request->product,
            'image' => $review_image,
            'rating'=> $request->rating,
            'seller_id'=>$request->seller_id
        ]);
        return 'success';
    }

    public function review($id){
        $review = Review::with('user')->where('product_id',$id)->whereNotNull('comment')->where('status','publish')->orderBy('created_at','DESC')->get();
        return $review;
    }
}
