<?php

namespace App\Http\Controllers;

use App\Models\Discrete_time;
use Illuminate\Http\Request;
use PhpParser\Node\Scalar\MagicConst\Dir;
use PhpParser\Node\Stmt\Foreach_;

class DiscreteTimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(int $id_of_time)
    {
        return Discrete_time::where('id_of_time',$id_of_time)->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    public function store(Request $request,int $id_of_time)
    {
        $values=$request->validate([
            "start_time"=> 'required|date_format:H:i',
            "end_time"=> 'required|date_format:H:i|after:time_start'
        ]);
        $values['id_of_time']=$id_of_time;
        Discrete_time::create($values);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Discrete_time  $discrete_time
     * @return \Illuminate\Http\Response
     */
    public function show(Discrete_time $discrete_time)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Discrete_time  $discrete_time
     * @return \Illuminate\Http\Response
     */
    public function edit(Discrete_time $discrete_time)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Discrete_time  $discrete_time
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Discrete_time  $discrete_time
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $values=$request->validate([
            "start_time"=> 'required|date_format:H:i',
            "end_time"=> 'required|date_format:H:i|after:time_start',
            'id_of_time' => 'required|integer|digits_between:1,11'
        ]);
        $time=Discrete_time::where('id_of_time',$values['id_of_time'])->where('start_time',$values['start_time'])->first();
        $time->delete();
    }





    public function compere_tow_date($start_time,$end_time){

        if(!(intval($end_time->format('H'))-intval($start_time->format('H'))>=1)){

          abort(422,'end time should be greater than start time by 1 hour at least');
          }

          if(intval($end_time->format('H'))-intval($start_time->format('H'))==1){
            if(!(intval($end_time->format('i'))-intval($start_time->format('i') )>=1)){

              abort(422,'end time should be greater than start time by 1 hour at least');
            }

          }

      }





}
