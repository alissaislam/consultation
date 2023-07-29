<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Experience_and_description;
use Illuminate\Http\Request;

class ExperienceAndDescriptionController extends Controller
{
   //show all Experience
//    public function index(){
//     return ExperienceAndDescriptionsController::all();
//       }

//create new Experience
public static function create(Request $request){
$fields=$request->validate([
   'name_of_experience'=>'required|string|max:255|min:0',
   'description'=>'required|string|min:0'
]);

if(!auth()->user()->is_expert)
    abort(422,'not expert');
$expert_id=auth()->user()->expert->expert_id;
$fields['expert_id']=$expert_id;
$exp=Experience_and_description::where('expert_id',$expert_id)->where('name_of_experience',$fields['name_of_experience'])->first();
//creation
if($exp)
    return response(["Message"=>"Created before make sure tht name is unique"]);
return Experience_and_description::create($fields);
}
//show  Experiences for one expert
public static function show($expert_id){
$experience=Experience_and_description::where('expert_id',$expert_id);
if(!$experience)
return response(["message"=>"experience not found"]);
else
return $experience;
}
//update experience
public static function update(Request $request,$exp_id)
{
    $experience=Experience_and_description::where('exp_id',$exp_id);

    if(!$experience)
    return response(["message"=>"experience not found"]);
    else
    $fields = $request->validate([
        'name_of_Experience'=>'required|string|max:255',
        'description'=>'required|string'
    ]);
    if(!auth()->user()->is_expert)
     abort(422,'not expert');
     $expert_id=auth()->user()->expert->expert_id;
    $fields['expert_id']=$expert_id;
    $experience->update($fields);
    return $experience;
}
//delete experience






public static function destroy(Request $request)
{
    $fields=$request->validate([
        'name_of_experience'=>'required|string|max:255',
     ]);
     if(!auth()->user()->is_expert)
        abort(422,'not expert');
     $expert_id=auth()->user()->expert->expert_id;
     $experience=Experience_and_description::where('expert_id',$expert_id)->where('name_of_experience',$fields['name_of_experience'])->first();
  //   $experience=Experience_and_description::find($fields['exp_id']);
     if(!$experience){return response(["Message" => "experience not found"]);}
     if(!$experience->expert_id==$expert_id)
        abort(403,"this experience does not belong to you hacker");

    return $experience->delete();
}
}
