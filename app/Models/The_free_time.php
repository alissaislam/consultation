<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class The_free_time extends Model
{
    use HasFactory;
    protected  $guarded = [];
    protected $primaryKey="id_of_time";
    public $timestamps = false;
    /**
     * Get all of the comments for the The_free_time
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function discrete_time()
    {
        return $this->hasMany(Discrete_time::class, 'id_of_time', 'id_of_time');
    }
    public function expert(){
        return $this->belongsTo(Expert::class,'expert_id','expert_id');
    }
}
