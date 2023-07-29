<?php

namespace App\Http\Controllers;

use App\Models\Favorite_expert;
use App\Models\Normal_user;
use App\Models\Review_expert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class NormalUserController extends Controller
{
    public static function index(){

        $data=auth()->user()->load('expert.consultations.type_of_consulatation','phone_numbers','expert.experience')->makeVisible('wallet');
        if($data->expert)
            $data->expert->consultations->makeHidden('expert_id','id_of_consultations');

        return $data;
    }
    public function index_by_id(Request $request){
        $user=Normal_user::where('user_id', $request->id)->first();
        if(!$user)
            abort(404,'user not found');
        if($user->expert){
           $expert= $user->expert;
           $data= $user->load('expert.consultations.type_of_consulatation','phone_numbers','expert.experience')->makeHidden('wallet','expert.consultations.type_of_consulatation.expert_id');
           $data->expert->consultations->makeHidden('expert_id','id_of_consultations');
        $count=0;
        $sum=0;
        foreach($expert->Review as $review){
        $sum+=$review->rating;
        $count++;
        }
        $finalrating=0;
        if($count!=0){
            $finalrating=$sum/$count;}
            $data['rating']=$finalrating;
            $favorite= Favorite_expert::where('user_id',auth()->user()->user_id)->where('expert_id',$request->id)->first();

            if($favorite){
                $data['is_favorite']=true;
            }
            else{  $data['is_favorite']=false;}

            $review=Review_expert::where('user_id',auth()->user()->user_id)->where('expert_id',$request->id)->first();
            if($review){
                $rating_by_this_user=$review->rating;
                $data['rating_by_this_user']=$rating_by_this_user;
                $data['is_rated_by_this_user']=true;
            }
            else{
                $data['rating_by_this_user']=0;
                $data['is_rated_by_this_user']=false;
                }


        return $data;
        }
        //if it is a normal user account return just the name and user stuff
        else{
            return $user;
        }
    }
    public function signup_as_normal_user_account(Request $request,PhoneNumbersForUserController $phone){
        $vlaues=$request->validate([
            'first_name' => 'required|string|min:3|max:30'
            ,'last_name' => 'required|string|min:3|max:30'
            , 'email' => 'required|string|email|unique:normal_users,email|max:255'
            , 'password' => 'required|string|min:8|max:255'

        ]);
        $request->validate([
            'phone_number' => 'required|digits_between:9,11',
            'country_number' => 'required|digits_between:1,3'
        ]);
        $vlaues['is_expert']=false;
        $vlaues['wallet']=10000;
        $this->uploadImage($request,$vlaues);
        $vlaues['password']=Hash::make($vlaues['password']);
        $user=Normal_user::create($vlaues);
        $phone->store($request,$user->user_id);
        $token=$this->login($request);
     return response()->json([$user->load('phone_numbers'),$token],201);
    }



    public function signup_as_expert_account(Request $request,PhoneNumbersForUserController $phone){
        $vlaues=$request->validate([
            'first_name' => 'required|string|min:3|max:30'
            ,'last_name' => 'required|string|min:3|max:30'
            , 'email' => 'required|string|email|unique:normal_users,email|max:255'
            , 'password' => 'required|string|min:8|max:255'

        ]);
        $request->validate([
            'phone_number' => 'required|digits_between:9,11',
            'country_number' => 'required|digits_between:1,3',
            'country'=>'required|min:2|max:55|string',
            'city'=>'required|min:2|max:55|string',
            'street'=>'|min:2|max:55|string',
            'hour_charging'=>'required|integer|min:0'
        ]);
        $vlaues['is_expert']=true;
        $vlaues['wallet']=0;
        $this->uploadImage($request,$vlaues);
        $vlaues['password']=Hash::make($vlaues['password']);
        $user=Normal_user::create($vlaues);
        //extra code for creating expert
        $phone->store($request,$user->user_id);

        $expert=ExpertController::create($request,$user);
        $expert->save();
        $token=$this->login($request);

     return response()->json([$user->load('expert','phone_numbers') ,$token],201);
    }
//upload image
    public function uploadImage($request, &$fields)
    {
        if ($request->hasFile('image')) {
            $fields['image'] = $request->file('image')->store('users_photos', 'public');
        } else {
            $fields['image'] = 'users_photos/default_image.png';
        }
    }

//login
public function login(Request $request){
    $request->validate([
        'email' => 'required|email|max:255',
        'password' => 'required|max:255',
        'device_name' => 'required|max:255',
    ]);

    $user = Normal_user::where('email', $request->email)->first();
     if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }
        $user->save();
        $token=$user->createToken($request->device_name)->plainTextToken;
    return $token;
}
//delete user from database
public function delete_user(Request $request){
    $request->validate([
        'email' => 'required|email|max:255',
        'password' => 'required|max:255',
    ]);

    $user = Normal_user::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'password' => ['The provided credentials are incorrect.'],
        ]);
    }
    $user->tokens()->delete();
    $user->delete();
}
public function logout_from_all_devices(){
    auth()->user()->tokens()->delete();

}

public function logout_from_this_device(){
    auth()->user()->currentAccessToken()->delete();
}


public function update_the_account(Request $request){
    $vlaues=$request->validate([
         'current_password' => 'required|string|min:8|max:255',
    ]);
    if(!Hash::check($vlaues['current_password'],auth()->user()->password)){
        abort(403,"current_password is not correct");
    }
    $update=$request->validate([
        'first_name' => 'string|min:3|max:30'
        ,'last_name' => 'string|min:3|max:30'
        , 'email' => 'string|email|unique:normal_users,email|max:255'
        , 'password' => 'string|min:8|max:255'
    ]);
    $this->uploadImage($request,$update);
    if(array_key_exists('password',$update)){
        $update['password']=Hash::make($update['password']);}
    $user=auth()->user();
    $user->update($update);
    if(auth()->user()->is_expert){
        ExpertController::update($request,auth()->user()->user_id);
    }
    //note that the user may not be expert so that the expert field will be null
    return $user->load('expert','phone_numbers');
}


}
