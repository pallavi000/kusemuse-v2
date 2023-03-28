<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Auth;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        if($request->gender){
            $gender = $request->gender;
        }else{
            $gender = '';
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'gender' => $gender
        ]);

        if(auth()->attempt(array('email' => $request->email, 'password' => $request->password)))
        {
            $token = auth()->user()->createToken('Laravel Password Grant Client')->accessToken;
            return response(['user'=>auth()->user(),'accessToken'=>$token]);

        }
        
    }

    protected function googlelogin(Request $request){


        $user = User::where('email', $request->email)->first();
        //Now log in the user if exists
        if ($user)
        {
            Auth::loginUsingId($user->id);
            $token = auth()->user()->createToken('Laravel Password Grant Client')->accessToken;
            return response(['user'=>auth()->user(),'accessToken'=>$token, 'from'=>'login']);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if(auth()->attempt(array('email' => $request->email,  'password' => $request->password )))
        {
            $token = auth()->user()->createToken('Laravel Password Grant Client')->accessToken;
            return response(['user'=>auth()->user(),'accessToken'=>$token, 'from'=>'register']);
        }

    }
}
