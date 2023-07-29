<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Expert;
use App\Models\Experts_consultation_joining_table;
use App\Models\Types_of_consultation;
use Illuminate\Http\Request;

class ExpertsConsultationJoiningTableController extends Controller
{

//delete relationship




// create new Relationship
public static function create_by_request(Request $request,TypesOfConsulatationController $typesOfConsulatationController ){
   $value= $request->validate([
       'name_of_consultations'=>'required|min:2|max:255|string',
    ]);
    //creation

    $fields['expert_id']=auth()->user()->expert->expert_id;
    $find_consult=$typesOfConsulatationController->show($value['name_of_consultations']);
    if($find_consult){
        $fields['id_of_consultations']=$find_consult->id_of_consultations;
        $exist=Experts_consultation_joining_table::where('expert_id',$fields['expert_id'])->where('id_of_consultations',$fields['id_of_consultations'])->first();
        if($exist){
            abort(422,'this consultation has already been added');
        }
        $relationship=Experts_consultation_joining_table::create($fields);
    }
    else{
        $newTypesOfConsulatation=$typesOfConsulatationController->create($request);
        $fields['id_of_consultations']=$newTypesOfConsulatation->id_of_consultations;
        $relationship=Experts_consultation_joining_table::create($fields);
    }

    return $relationship;
}





//delete relationship
public static function destroy(Request $request){
    $fields=$request->validate([
         'name_of_consultations'=>'required|string'
      ]);
      if(!auth()->user()->expert){
        abort(422,'you are not an expert');
      }
      $fields['expert_id']=auth()->user()->expert->expert_id;
       $id_of_consultations= Types_of_consultation::where('name_of_consultations',$fields['name_of_consultations'])->first()->id_of_consultations;
       $relationship=Experts_consultation_joining_table::where('expert_id',$fields['expert_id'])->where('id_of_consultations',$id_of_consultations);
       $relationship->delete();
}
public static function destroyAll(Request $request){
    $fields=$request->validate([
        'expert_id'=>'required|integer',
      ]);
       $relationship=Experts_consultation_joining_table::where('expert_id',$fields['expert_id']);
       $relationship->destroy();
}
public static function find(Request $request){
    $fields=$request->validate([
        'id_of_consultations'=>'required|integer',
      ]);
      $experts_id=Experts_consultation_joining_table::where('id_of_consultations',$fields['id_of_consultations'])->get();
      $experts=[];
foreach($experts_id as $id){
    $expert=Expert::find($id)->load('normal_user');
    array_push($experts,$expert);
}
return $experts;
}

}

