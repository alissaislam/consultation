<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite_expert extends Model
{
    use HasFactory;
    protected $guarded=[];
    public $timestamps = false;
    public function expert()
    {
        return $this->hasOne(Expert::class,'expert_id','expert_id');
    }
    public function user()
    {
        return $this->hasOne(Normal_user::class,'user_id','user_id');
    }
}
