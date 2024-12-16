<?php

namespace App\Models;

use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    
    protected $fillable = [
        'doctor_id',
        'patient_id',
        'appointment_date',
        'start_time',
        'end_time',
    ];


    // Define relationships
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
}
