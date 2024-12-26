<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'appointment_id',
        'pid',
        'amount',
        'status',
        'payment_method',
    ];

    /**
     * Get the order that owns the payment.
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class,'appointment_id');
    }
}
