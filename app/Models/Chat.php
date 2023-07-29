<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $primaryKey="chat_id";



    public function Chat_participant(){
        return $this->hasMany(Chat_participant::class,'user_id','user_id');
     }

     public function Chat_message(){
        return $this->hasMany(Chat_message::class,'user_id','user_id');
     }

     public function last_message(){
        return $this->hasOne(Chat_message::class,'user_id','user_id')->latest('updated_at');
     }
     public function scopeHasParticipant($query ,int $user_id){
        return $query->whereHas('Chat_participant',function ($q) use ($user_id){
            $q->where('user_id',$user_id);
        });
     }

}
