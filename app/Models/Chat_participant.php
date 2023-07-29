<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat_participant extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $primaryKey="chat_participants_id";
    public $timestamps = false;
    public function normal_user(){
        return $this->belongsTo(Normal_user::class,'user_id','user_id');
     }



}
