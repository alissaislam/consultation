<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booked_date extends Model
{
    use HasFactory;
    protected $guarded=[];
    public $timestamps = false;
    public $incrementing = false;
    public function expert(){
        return $this->belongsTo(Expert::class,'expert_id','expert_id');
    }
    public function normal_user()
    {
      return $this->belongsTo(Normal_user::class,'user_id','user_id');
    }
}
