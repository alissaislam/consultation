<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discrete_time extends Model
{
    use HasFactory;
    protected  $guarded = [];
    public $timestamps = false;

    public function the_free_time()
    {
        return $this->belongsTo(The_free_time::class, 'id_of_time', 'id_of_time');
    }
}
