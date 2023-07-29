<?php

namespace App\Http\Controllers;

use App\Models\Expert;
use Illuminate\Http\Request;
use App\Models\Favorite_expert;
use App\Http\Controllers\Controller;

class FavoriteExpertController extends Controller
{
    //create new favorite
public static function create(Request $request){

    $fields=$request->validate([
       'expert_id'=>'required|integer|digits_between:1,11'
    ]);
    $fields['user_id']=auth()->user()->user_id;
    $expert=Expert::find($fields['expert_id']);
    if($fields['user_id']==$fields['expert_id'])   {
         return response(["message"=>"you can't like your self"]);
    }
   $favorite= Favorite_expert::where('user_id',$fields['user_id'])->where('expert_id',$fields['expert_id'])->first();
    if($favorite)
    return response(["message"=>"already liked"]);
    if(!$expert)
      return response(["message"=>"Expert not found"]);
      else{
    //creation
    Favorite_expert::create($fields);
      }
    }
    //get all my Favorites experts
    public static function myFavorite(){
        $favorites=Favorite_expert::where('user_id',auth()->user()->user_id)->get();
        $favorites->load('expert.normal_user');
        return $favorites;
    }
    //delet single expert
    public static function destroy(Request $request){
        $expert_id=$request->validate([
            'expert_id'=>'required|integer|digits_between:1,11'
         ]);

         $user_id=auth()->user()->user_id;
         $expert=Expert::find($expert_id['expert_id']);

         if(!$expert)
           return response(["message"=>"Expert not found"]);
           else{
           $not_my_favorite= Favorite_expert::where('user_id',$user_id)->where('expert_id',$expert_id['expert_id']);
            if(!$not_my_favorite){
                return response(["message"=>"Expert not found"]);
            }
           return $not_my_favorite->delete();
           }

    }
}
