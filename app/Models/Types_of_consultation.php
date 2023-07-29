<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Types_of_consultation extends Model
{
    use HasFactory;
    protected  $guarded = [];
    protected $primaryKey="id_of_consultations";
    public $timestamps = false;

    public function experts(){
        return $this->hasMany(Experts_consultation_joining_table::class,'id_of_consultations','id_of_consultations');
     }



}
