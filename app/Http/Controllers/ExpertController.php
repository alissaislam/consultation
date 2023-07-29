<?php

namespace App\Http\Controllers;

use App\Models\Expert;
use App\Models\Normal_user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpertController extends Controller
{
    public function index(){
        $expert=Expert::all()->load('normal_user');

        foreach($expert as $exp){
            $exp->normal_user;
            $exp->load('consultations.type_of_consulatation');
            $count=0;
            $sum=0;
            foreach($exp->Review as $review){
            $sum+=$review->rating;
            $count++;
          //  $exp[];
            }
            $finalrating=0;
            if($count!=0){
            $finalrating=$sum/$count;}
            $exp['rating']=$finalrating;
        }
        foreach($expert as $exp){
            $exp->makeHidden('Review');
        }
        return $expert;
    }
   //create new expert
   public static function create(Request $request,Normal_user $user){
    $fields=$request->validate([
      'country'=>'required|min:2|max:55|string',
       'city'=>'required|min:2|max:55|string',
       'street'=>'|min:2|max:55|string',
       'hour_charging'=>'required|integer'
    ]);
    $fields['expert_id']=$user->user_id;
    //creation
    $expert=Expert::create($fields);
    return $expert;
}
//show singal expert
public function show($expert_id){
  $expert=Expert::find($expert_id);
  if(!$expert)
    return response(["message"=>"Expert not found"]);
    else
  return $expert;

}
//update expert
public static function update(Request $request, $id)
{
    $expert=Expert::find($id);

    if(!$expert)
    return response(["message"=>"Expert not found"]);
    else
    $fields = $request->validate([
       'country'=>'min:2|max:55|string',
       'city'=>'min:2|max:55|string',
       'street'=>'min:2|max:55|string',
       'hour_charging'=>'integer'
    ]);
    $expert->update($fields);
    return $expert;
}
//find expert
public function find(Request $request){

  $fields=$request->validate([
    'name'=>'required|string|max:255'
  ]);

  $expert=Normal_user::where(DB::raw("concat(first_name, ' ', last_name)"),'like','%' .$fields['name']. '%')->orWhere('last_name','like','%' .$fields['name']. '%')->where('is_expert','1')->get();
  // $expert=Expert::where('first_name',$request->name);
 if(count($expert)==0)
    return response(["message"=>"Expert not found"],404);
    else
  return $expert;
}
//delete expert
//this has a security problem
/*public function destroy($id)
{
    return Expert::destroy($id);
}
*/
}
