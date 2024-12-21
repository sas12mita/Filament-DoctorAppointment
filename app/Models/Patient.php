<?php

namespace App\Models;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $guarded = [];
    public function  user(){
        return $this->belongsTo(User::class,'user_id');

    }
    public function bookappointments(){
        return $this->hasMany(Appointment::class);
    }
}
