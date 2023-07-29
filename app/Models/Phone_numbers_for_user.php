<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phone_numbers_for_user extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $primaryKey="phone_id";
    public $timestamps = false;
    public function normal_user()
    {
        return $this->belongsTo(Normal_user::class);
    }
}
