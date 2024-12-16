<?php

namespace App\Models;

use App\Models\Appointment;
use App\Models\Schedule;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $guarded = [];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function specialization()
    {
        return $this->belongsTo(Specialization::class,'specialization_id'); // assuming 'specialization_id' exists
    }
}
