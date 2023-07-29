<?php

namespace App\Http\Controllers;

use App\Models\The_free_time;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Cast\Double;
use Ramsey\Collection\Map\AbstractMap;
use Symfony\Component\VarDumper\Cloner\Data as ClonerData;

class TheFreeTimeController extends Controller
{
    public function index(int $expert_id){

      $value=The_free_time::where('expert_id', $expert_id)->get();
      $array=array();
      foreach($value as  $day){
          $array[]=$day->load('discrete_time')->makeHidden('expert_id');
        }
      return $array;
    }


   //create new free time
public function create(Request $request,DiscreteTimeController $controller){
  $fields=$request->validate([
       'day_of_free'=>'required|integer|lte:6|gte:0',
        'start_time'=>'required|date_format:H:i',
        'end_time'=>'required|date_format:H:i'
      ]);
    if(!auth()->user()->expert)
      abort(422,'user not authenticated');
    $fields['expert_id']=auth()->user()->expert->expert_id;

    $start_time =  date_create_from_format("H:i",$fields['start_time']);
    $end_time = date_create_from_format('H:i',$fields['end_time']);

    $controller->compere_tow_date($start_time,$end_time);

     $day= The_free_time::where('day_of_free',$fields['day_of_free'])->where('expert_id',$fields['expert_id'])->first();

      if($day){
        $time=$controller->index($day->id_of_time);
        foreach($time as $one_time){
            //check for collision
            if(!(intval($start_time->format('H'))<intval(date_create_from_format('H:i:s',$one_time['start_time'])->format('H'))-1
            ||intval($start_time->format('H'))>intval(date_create_from_format('H:i:s',$one_time['end_time'])->format('H'))+1)){
                abort(404,'start time collision with time store before');
            }

            if(!(intval($end_time->format('H'))<intval(date_create_from_format('H:i:s',$one_time['start_time'])->format('H'))-1
            ||intval($end_time->format('H'))>intval(date_create_from_format('H:i:s',$one_time['end_time'])->format('H'))+1)){
                abort(404,'end time collision with time store before');

            }

        }
        $controller->store($request,$day->id_of_time);
        return $day->load('discrete_time');
      }
      else {
        $new_day=The_free_time::create(['expert_id'=>$fields['expert_id'],'day_of_free'=>$fields['day_of_free']]);
        $controller->store($request,$new_day->id_of_time);

        return $new_day->load('discrete_time');
      }

}
//show signal free time
public function show($id_of_time){
  $free_time=The_Free_Time::find($id_of_time);
  if(!$free_time)
    return response(["message"=>"free time not found"],404);
    else
  return $free_time;
}
//update free time
public static function update(Request $request, $id_of_time)
{
    $free_time=The_Free_Time::find($id_of_time);
    if(!$free_time)
    return response(["message"=>"free time not found"]);
    else
    $fields = $request->validate([
        'day_of_free'=>'required|integer|lte:6|gte:0',
    ]);
    $free_time->update($fields);
    return $free_time;
}
//delete free time
public static function destroy($id_of_time)
{

    return The_Free_Time::destroy($id_of_time);
}







}
