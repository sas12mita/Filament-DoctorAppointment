<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'doctor_id',
        'patient_id',
        'schedule_id',
        'start_time',
    ];
        public function patient()
        {
            return $this->belongsTo(Patient::class, 'patient_id');
        }
    
        public function doctor()
        {
            return $this->belongsTo(Doctor::class, 'doctor_id');
        }
        public function patientreports()
        {
            return $this->hasMany(PatientReport::class);
        }
        public function schedule()
        {
            return $this->belongsTo(Schedule::class, 'schedule_id');
        }
    
}
