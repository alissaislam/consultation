<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience_and_description extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $primaryKey="exp_id";
    public $timestamps = false;
    public function expert()
    {
        return $this->belongsTo(Expert::class);
    }
}
