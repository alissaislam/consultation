<?php

namespace App\Models;

use App\Http\Controllers\ExpertsConsultationJoiningTableController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expert extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $primaryKey="expert_id";
    public $incrementing = false;
    public $timestamps = false;
    /**
     * Get the user te Expert
     *
     * @return \Illuminate\Normal_userbase\Esoquent\Relations\BelongsTo
     */
    public function normal_user()
    {
      return $this->belongsTo(Normal_user::class,'expert_id','user_id');
    }


     public function free_time(){
        return $this->hasMany(The_free_time::class,'expert_id','expert_id');
     }

     public function experience(){
        return $this->hasMany(Experience_and_description::class,'expert_id','expert_id');
     }


      public function booked_dates(){
        return $this->hasMany(Booked_date::class,'expert_id','expert_id');
     }


     /**
      * Get all of the comments fpert
      *
      * @return \Illuminate\Database\Eloquent\Relations\HasMany
      */
     public function consultations()
     {
         return $this->hasMany(Experts_consultation_joining_table::class, 'expert_id', 'expert_id');
     }

     public function favorite(){
        return $this->hasMany(Favorite_expert::class,'expert_id','expert_id');
     }

     public function Review(){
        return $this->hasMany(Review_expert::class,'expert_id','expert_id');
     }


}
