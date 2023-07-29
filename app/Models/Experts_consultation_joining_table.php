<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experts_consultation_joining_table extends Model
{
    use HasFactory;
    protected $guarded=[];
    public $timestamps = false;
    public $incrementing = false;
    public function expert()
    {
        return $this->hasOne(Expert::class,'expert_id','expert_id');
    }
    public function type_of_consulatation()
    {
        return $this->hasOne(Types_of_consultation::class,'id_of_consultations','id_of_consultations');
    }
}
