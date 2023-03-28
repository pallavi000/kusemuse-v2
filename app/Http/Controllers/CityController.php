<?php

namespace App\Http\Controllers;
use Session;
use App\Models\City;
use App\Models\Country;

use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cities = City::with('country')->get();



        return view('admin.city.index',compact('cities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::all();
        return view('admin.city.create',compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required',
            'country'=>'required'
        ]);

        City::create([
            'name'=> $request->name,
            'country_id'=>$request->country
        ]);
        Session::flash('success', 'City created successfully');
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
        $city = City::with('country')->findorfail($id)->first();
        $countries= Country::all();
        return view('admin.city.edit',compact('countries','city'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, City $city)
    {
        $this->validate($request,[
            'name'=>'required',
            'country'=>'required'
        ]);
        $city->name= $request->name;
        $city->country_id= $request->country;
        $city->save();

        Session::flash('success', 'City updated successfully');
        return redirect()->back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city)
    {
        if($city){
            $city->delete();
            Session::flash('success', 'City deleted successfully');
        }

        return redirect()->back();
    }

    
 public function cities(){
     $cities = City::with('country')->get();
     $country = Country::findorfail(1);
    return json_encode(array('cities'=>$cities,'country'=>$country));
    }

}
