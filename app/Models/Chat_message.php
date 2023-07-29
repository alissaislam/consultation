<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat_message extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $primaryKey="chat_id";
    protected $touches=['chat'];
    public function normal_user(){
        return $this->belongsTo(Normal_user::class,'user_id','user_id');
     }


     public function chat(){
        return $this->belongsTo(Chat::class,'chat_id','chat_id');
     }

}
