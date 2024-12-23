<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
    protected function handleRecordCreation(array $data): Model
    {
        
     $user = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => bcrypt($data['password']),
        'phone' => $data['phone'],
        'address' => $data['address'],
        'role' => $data['role'], 
        'gender'=>$data['gender']
    ]);
    if ($data['role'] === 'patient') {
        Patient::create([
            'user_id' => $user->id, 
        ]);
    }
    if ($data['role'] === 'doctor') { 
        Doctor::create([
            'user_id' => $user->id,
            'specialization_id' => $data['specialization_id'], 
        ]);
    }
    return $user;
    }
}
