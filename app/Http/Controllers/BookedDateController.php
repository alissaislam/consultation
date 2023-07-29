<?php

namespace App\Http\Controllers;

use App\Models\Booked_date;
use App\Models\Expert;
use App\Models\Normal_user;
use App\Models\The_free_time;
use Illuminate\Http\Request;

class BookedDateController extends Controller
{

public function index(){
$expert=auth()->user()->expert;
if(!$expert)
abort(422,'you are not an expert');
$expert_id=$expert->expert_id;

$all_booked_dates=Booked_date::where('expert_id',$expert_id)->get();
$all_booked_dates->load('normal_user');

return $all_booked_dates;
}














public function get_free_times_with_booked_date(Request $request){
$values=$request->validate([
"date_of_chosen_day"=>"required|date_format:Y:m:d",
"expert_id"=> "required|digits_between:1,11"
]);

$date_passes_by_user=date_create_from_format('Y:m:d',$values['date_of_chosen_day']);
$server_date=date_create_from_format('Y:m:d',date('Y:m:d'));


if(!($date_passes_by_user>$server_date)){
    abort(442,'you cannot booking a date in the past');
    }

    $expert= Expert::find($values['expert_id']);
    if(!$expert){abort(422,'the provide expert id is not on the system ');}
$time=The_free_time::where('expert_id',$expert->expert_id)->where('day_of_free',intval($date_passes_by_user->format('w')))->first();
if(!$time){abort(422,'date_passes_by_user is not on the system ');}
$j=0;
$array=array(array(array()));
foreach($time->discrete_time as $time_in_hours){

    $start_time=date_create_from_format('H:i:s',$time_in_hours['start_time']);
    $end_time=date_create_from_format('H:i:s',$time_in_hours['end_time']);
    $end_time_temp=$start_time;
    $check_for_collision_backword=date_create_from_format('Y:m:d:H:i:s',$date_passes_by_user->format('Y:m:d').':'.$start_time->format('H:i:s'));
    $counter=0;

    $array[$j][0]['start_time'] = $start_time->format('H:i:s');
    $array[$j][0]['end_time'] = $end_time->format('H:i:s');
    for($cheack_time=$start_time,$i=0;$cheack_time<=$end_time;$cheack_time->modify("+ 1 hour")){
        $stored_date=Booked_date::where('date_order_by_user',$check_for_collision_backword)->first();
        if($stored_date){
        $number_of_hour=$stored_date->number_of_booked_hours;
        $end=$stored_date['date_order_by_user'];

        $start=date_create_from_format('Y-m-d H:i:s',$end);
        $end=date_create_from_format('Y-m-d H:i:s',$end);

        $start->modify("+ $number_of_hour hour");
            $i++;
            $array[$j][$i]['start_time'] = $start->format('H:i:s');
            $array[$j][$i]['end_time']= $array[$j][$i-1]['end_time'];
            $array[$j][$i-1]['end_time'] = $end->format('H:i:s');
            $counter++;
    }
        $check_for_collision_backword->modify("+ 1 hour");

    }
for($i=0;$i<=$counter;$i++){
    if( $array[$j][$i]['start_time']==$array[$j][$i]['end_time']){
       // unset($array[$j][$i]['start_time']);
        unset($array[$j][$i]);
    }
    if(sizeof($array[$j])==0){
        unset($array[$j]);
    }
}



    $j++;

 }


 return $array;


}









public function create(Request $request){

    $data=$request->validate([
        "expert_id"=> "required|digits_between:1,11"
        ,"date_order_by_user"=>"required|date_format:Y-m-d"
        ,"start_time"=>"required|date_format:H:i"
        ,"number_of_booked_hours"=>"required|date_format:H"
    ]);

    $merge= date_create_from_format('Y-m-d:H:i',$data['date_order_by_user'].':'.$data['start_time']);
    $values=$request->validate([
        "expert_id"=> "required|digits_between:1,11"
        ,"date_order_by_user"=>"required|date_format:Y-m-d"
        ,"number_of_booked_hours"=>"required|date_format:H"
    ]);
    $values['date_order_by_user']=$merge->format('Y:m:d:H:i');

    if(auth()->user()->user_id==$values['expert_id'])    {
          abort(422,'you cannot booked a date with your self');
    }


    $number_of_booked_hours=$values['number_of_booked_hours'];
$values['user_id'] = auth()->user()->user_id;
$date_passes_by_user=date_create_from_format('Y:m:d:H:i',$values['date_order_by_user']);
$server_date=date_create_from_format('Y:m:d:H:i',date('Y:m:d:H:i'));

if(!($date_passes_by_user>$server_date)){
abort(442,'you cannot booking a date in the past');
}

$expert= Expert::find($values['expert_id']);
if(!$expert){abort(422,'the provide expert id is not on the system ');}
$expert->load('free_time');
//bool found

$formathour=date_create_from_format('H:i:s',$date_passes_by_user->format('H:i:s'));
$cheack_for_over_end_time=$formathour;
foreach($expert->free_time as $time){
if($time->day_of_free==intval($date_passes_by_user->format('w'))){
    foreach($time->discrete_time as $time_in_hours){
        $start_time=date_create_from_format('H:i:s',$time_in_hours['start_time']);
        $end_time=date_create_from_format('H:i:s',$time_in_hours['end_time']);
        $check_for_collision_backword=date_create_from_format('Y:m:d:H:i:s',$date_passes_by_user->format('Y:m:d').':'.$start_time->format('H:i:s'));

        $check_for_collision_forword=date_create_from_format('Y:m:d:H:i',$values['date_order_by_user']);

        if($start_time<=$formathour&&$formathour<=$end_time){
            if(intval($start_time->format('i'))!=intval($formathour->format('i'))){
                abort(422,'you cannot take minutes different from minutes that expert provided');
            }
            if($cheack_for_over_end_time->modify("+ $number_of_booked_hours hour")>$end_time){
                abort(422,'you take more than allowed hours by the end time');
            }
            for($cheack_time=$start_time;$cheack_time<=$end_time;$cheack_time->modify("+ 1 hour")){
                $stored_date=Booked_date::where('date_order_by_user',$check_for_collision_backword)->first();

                if($stored_date){
                $number_of_hour=$stored_date->number_of_booked_hours;
                $start=$stored_date['date_order_by_user'];
                $end_time=date_create_from_format('Y-m-d H:i:s',$start);
                $start=date_create_from_format('Y-m-d H:i:s',$start);
                $end_time->modify("+ $number_of_hour hour");
                if($start<=$date_passes_by_user&&$date_passes_by_user<$end_time){
                    abort(422,'the date already has been taken');
                }

            }
                $check_for_collision_backword->modify("+ 1 hour");

            }
            for($i=0;$i<intval($number_of_booked_hours);$i++){
                $stored_date=Booked_date::where('date_order_by_user',$check_for_collision_forword)->first();
                if($stored_date){
                    abort(422,'the number of booked hours is conflict with a booked date');
                }
                $check_for_collision_forword->modify("+ 1 hour");
            }

            $this->withdraw($values['user_id'],$values['expert_id'],$number_of_booked_hours);
            return Booked_date::create($values);
        }

    }

}

}
abort(422,'the date provide is not in the free time of the expert');
}






public function withdraw($user_id,$expert_id,$number_of_hours){
$user=Normal_user::where('user_id',$user_id)->first();

$user_expert=Normal_user::where('user_id',$expert_id)->first();
if($user->wallet<$user_expert->expert->hour_charging*$number_of_hours){abort(422,'you cannot afford to pay to the expert');}
$user->wallet-=$user_expert->expert->hour_charging*$number_of_hours;
$user_expert->wallet+=$user_expert->expert->hour_charging*$number_of_hours;

$user->save();
$user_expert->save();
}


















}
