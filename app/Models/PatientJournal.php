<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientJournal extends Model
{
    protected $table = 'patient_journals';

    protected $fillable = ['appointment_id', 'report'];

    public function bookappointment()
    {
        return $this->belongsTo(Appointment::class,'appointment_id');
    }
    
}
