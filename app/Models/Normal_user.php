<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Normal_user extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected  $guarded = [];
    protected $primaryKey="user_id";
    protected $hidden = [
        'password',
        'email',
        'wallet'
    ];

  // protected $with=['expert'];
    /**
     * Get the user ad with the Normal_user
     *
     * @return \IlluminaExpertatabase\Eloquent\Relations\HasOne
     */
    public function expert()
    {
        return $this->hasOne(Expert::class,'expert_id','user_id');
    }

    public function phone_numbers()
    {
        return $this->hasMany(Phone_numbers_for_user::class,'user_id','user_id');
    }

    public function booked_dates(){
        return $this->hasMany(Booked_date::class,'user_id','user_id');
     }
     public function favorite(){
        return $this->hasMany(Favorite_expert::class,'user_id','user_id');
     }


     public function Review(){
        return $this->hasMany(Review_expert::class,'user_id','user_id');
     }
     public function chat(){
        return $this->hasMany(Chat::class,'user_id','user_id');
     }

     public function Chat_participant(){
        return $this->hasMany(Chat_participant::class,'user_id','user_id');
     }

     public function Chat_message(){
        return $this->hasMany(Chat_message::class,'user_id','user_id');
     }
}
