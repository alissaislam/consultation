<?php

namespace App\Http\Controllers;

use App\Models\Expert;
use App\Models\Review_expert;
use Illuminate\Http\Request;

class ReviewExpertController extends Controller
{
    public function addReview(Request $request){
        $values=$request->validate([
            "expert_id"=>"required|integer|digits_between:1,11"
           , "rating"=>"required|numeric|max:5"
        ]);
        $values['user_id']=$user_id=auth()->user()->user_id;
        if($user_id==$values['expert_id']){
            return response('you cannot review your self');
        }
        $expert=Expert::find($values['expert_id']);
        if(!$expert){
            return response('Expert not found');
        }
        $review=Review_expert::where('user_id',$user_id)->where('expert_id',$expert->expert_id);
    if($review->first()){
        $review->update($values);
    }
    else{
        Review_expert::create($values);
    }
}

public function removeReview(Request $request){
    $values=$request->validate([
        "expert_id"=>"required|integer|digits_between:1,11"
    ]);
    $values['user_id']=$user_id=auth()->user()->user_id;
    if($user_id==$values['expert_id']){
        return response('you cannot review your self');
    }
    $expert=Expert::find($values['expert_id']);
    if(!$expert){
        return response('Expert not found');
    }
    $review=Review_expert::where('user_id',$user_id)->where('expert_id',$expert->expert_id);
if($review->first()){
    $review->delete();
}
else{
    return response('review not found');
    }
}
}
