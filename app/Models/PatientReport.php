<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientReport extends Model
{
    protected $fillable = [
        'appointment_id', 
        'file', 
       ];

    /**
     * Relationship to the User model.
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class,'appointment_id');
    }
}
