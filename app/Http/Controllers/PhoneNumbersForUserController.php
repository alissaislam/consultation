<?php

namespace App\Http\Controllers;

use App\Models\Phone_numbers_for_user;
use Illuminate\Http\Request;

class PhoneNumbersForUserController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $vlaues=$request->validate([
            'user_id' => 'required|integer'
        ]);

        return Phone_numbers_for_user::where('user_id',$vlaues['user_id'])->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function store(Request $request,int $user_id)
    {

        $vlaues=$request->validate([
            'phone_number' => 'required|digits_between:9,11',
             'country_number' => 'required|digits_between:1,3'
        ]);
        $vlaues['user_id']=$user_id;
       Phone_numbers_for_user::create($vlaues)->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\phone_numbers_for_users  $phone_numbers_for_users
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\phone_numbers_for_users  $phone_numbers_for_users
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\phone_numbers_for_users  $phone_numbers_for_users
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\phone_numbers_for_users  $phone_numbers_for_users
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $vlaues=$request->validate([
            'phone_number' => 'required|digits_between:10,11'
        ]);
        $user_id=auth()->user()->user_id;

        $phone=Phone_numbers_for_user::where('phone_number',$vlaues['phone_number']);
        if($phone->user_id!=$user_id){abort(403,'the number is not belongs to the same person');}

        $phone->delete();

}
}
