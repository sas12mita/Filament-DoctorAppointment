<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'doctor_id',
        'date',
        'start_time',
        'end_time',
        'day',
        'status',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
    public function Appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
