<?php

namespace App\Http\Controllers;
use App\Models\User;
use Session;
use App\Models\Brand;
use App\Models\Designer;
use App\Models\Product;
use Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Mail\ForgotPassword;
use Illuminate\Support\Facades\Mail;


class UserController extends Controller
{



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('is_admin',1)->get();
        $sellers = User::where('role','boutique_seller')->orWhere('role','brand_seller')->get();
        return view('admin.user.index', compact('users','sellers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.user.create');
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            
        ]);

        if($request->role == 'admin'){
            $seller= NULL;
            $admin = 1;
        }else{
            $seller= $request->role;
            $admin = NULL;
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'is_admin' => $admin,
            'role' => $seller,
        ]);
      
        // create brand for user/seller
    if($request->role == 'brand_seller'){
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
    }

    // create designer for seller/user
    if($request->role == 'boutique_seller'){
        $designer = Designer::create([
            'name'=> $request->name,
            'slug'=> Str::slug($request->name, '-'),
            'seller_id'=>$user->id
        ]);
    }
        
        //$user->save();
        Session::flash('success', 'User created successfully');
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
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user= User::findorfail($id);
        return view('admin.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email->{{$id}}",
            'password' => 'sometimes|nullable|min:8',
        ]);
        $user = User::findorfail($id);
      

        if($user->role == "brand_seller"){
            $brand = Brand::where('name',$user->name)->first();
            if(!is_null($brand)){
            $brand->name = $request->name;
            $brand->slug = Str::slug($request->name, '-');
            $brand->save();
            }
        }

        if($user->role== "boutique_seller"){
            $designer = Designer::where('seller_id',$user->id)->first();
            if(!is_null($designer)){
                $designer->name = $request->name;
                $designer->slug = Str::slug($request->name, '-');
                $designer->save();
            }
           

        }
        $user->name = $request->name;
        $user->email = $request->email;
       
       if($request->password){
        $user->password = bcrypt($request->password);

       }
        $user->save();
        Session::flash('success', 'User updated successfully');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    $user= User::findorfail($id);
        
        if($user){
            // delete brand related to user
            if($user->role =="brand_seller"){
                $brands = Brand::where('name',$user->name)->first();
                if(!is_null($brands)){
                    $brands->delete();

                }
            }
            // delete designer related to user
            if($user->role=="boutique_seller"){
                $designer= Designer::where('seller_id',$user->id)->first();
                if(!is_null($designer)){
                    $designer->delete(); 

                }
            }
            // delete products related to user
            $products = Product::where('seller_id',$user->id)->get();
            foreach($products as $product){
                $product->visible=false;
                $product->save();

            }
            $user->delete();
            Session::flash('success', 'User deleted successfully');
        }
        return redirect()->back();
    }


    public function changepassword(Request $request){
        $this->validate($request, [
            'newpassword' => 'required|min:8',
        ]);
        if(Auth::guard('web')->attempt(array('email' => auth()->user()->email, 'password' => $request->currentpassword)))
        {
            $user = User::where('id',auth()->user()->id)->first();
            $user->password = Hash::make($request->newpassword);
            $user->save();
            return 'success';
        }else{
            return $request->currentpassword; 
        }
        return $request;
    }


    // forgot password
    public function verifyemail(Request $request){
        $user = User::where('email', $request->email)->first();
        if(is_null($user)){
            return abort(400);
        }

        function generateRandomString($length = 15) {
            return substr(str_shuffle(str_repeat($x= '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' , ceil($length/strlen($x)) )), 1 ,$length);
        }

        //VerifyKey 
        $verifyKey = generateRandomString(25);
        $user->verifykey = $verifyKey;
        $user->save();

        //Mail
        $details = [
            'forgot'=> $verifyKey
        ];
        Mail::to($request->email)->send(new ForgotPassword($details));
        return 'success';
    }

    // forgot password| password update
    public function setpassword(Request $request){
        $this->validate($request, [
            'password' => 'required|min:8',
        ]);
        if($request->key){
            $user = User::where('verifykey',$request->key)->first();
            if(is_null($user)){
                return abort(400);
            }

            $user->password = Hash::make($request->password);
            $user->verifykey= NULL;
            $user->save();

            if(Auth::guard('web')->attempt(array('email' => $user->email, 'password' => $request->password)))
            {
                $token = auth()->user()->createToken('Laravel Password Grant Client')->accessToken;
                return response($token);
            }else{
                return abort(500);
            }

        }
        return abort(404);
       

    }
}

