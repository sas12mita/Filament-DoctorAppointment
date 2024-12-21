<?php

namespace App\Filament\Resources\AppointemntResource\Pages;

use App\Filament\Resources\AppointemntResource;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateAppointemnt extends CreateRecord
{
    protected static string $resource = AppointemntResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {

        if (Auth::user()->role=='patient') {
        $user_id=Auth::user()->id;
        $patient=Patient::where('user_id',$user_id)->first();
        $data['patient_id'] = $patient->id;

        $appointment=Appointment::create(
            [
                'patient_id'=>$data['patient_id'],
                'doctor_id' => $data['doctor_id'],
                'schedule_id' => $data['schedule_id'],
                'start_time' => $data['start_time'],
                'status' => $data['status'] ?? 'pending',
            ]
            );
        

        }
        return $data;
    }
}
