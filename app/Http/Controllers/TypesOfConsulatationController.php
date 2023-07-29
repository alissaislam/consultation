<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Types_of_consultation;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;

class TypesOfConsulatationController extends Controller
{
    //show all consultations
    public static function index(){
        return Types_of_consultation::all();
          }
 //create new consultations
 public static function create(Request $request){
    $fields=$request->validate([
       'name_of_consultations'=>'required|string|max:255',
    ]);
    //creation
   return Types_of_consultation::create($fields);
}
//show singal consulatation
public static function show($name_of_consulatations){
  $consulatation=Types_of_consultation::where('name_of_consultations',$name_of_consulatations)->first();
  return $consulatation;
}
//find consulatation
public static function find(Request $request){
$request->validate([
    "name_of_consultations"=>'required|string|max:255'
]);
  $consulatation=Types_of_consultation::where('name_of_consultations','like','%' . $request->name_of_consultations . '%')->get();
  if(count($consulatation)==0)
    return response(["message"=>"consulatation not found"]);
    else
  return $consulatation;
}
// //update consulatation
// public function update(Request $request, $id_of_consulatations)
// {
//     $consulatation=Type_of_consulatations::find($id_of_consulatations);

//     if(!$consulatation)
//     return response(["message"=>"consulatation not found"]);
//     else
//     $fields = $request->validate([
//         'name_of_consulatations'=>'required|string',
//     ]);
//     $consulatation->update($fields);
//     return $consulatation;
// }
// //delete consulatation
// public function destroy($id_of_consulatations)
// {
//     return Type_of_consulatations::destroy($id_of_consulatations);
// }

public static function get_experts_by_id_of_consultations(Request $request){
    $fields=$request->validate([
         'id_of_consultations'=>'required|integer|digits_between:1,11'
      ]);

      $consultation=Types_of_consultation::find($fields['id_of_consultations']);
      if(!$consultation){
        return Response('consultation not found');
      }
      $consultation->load('experts.expert')->MakeHidden('id_of_consultations','name_of_consultations');
      $consultation->experts->MakeHidden('id_of_consultations','expert_id');
      return   $consultation->experts->load('expert.normal_user');
}



}
