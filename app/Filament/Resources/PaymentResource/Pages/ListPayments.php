<?php
namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use App\Models\Payment; // Import the Payment model
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder; // Import Builder for query building
use Illuminate\Support\Facades\Auth; // Import Auth for user authentication

class ListPayments extends ListRecords
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        $user = Auth::user();

        // If the user is a patient, show only their payments for their appointments
        if ($user->role === 'patient') {
            return Payment::query()
                ->whereHas('appointment', function ($query) use ($user) {
                    // Filter payments related to the patient's appointments
                    $query->whereHas('patient', function ($query) use ($user) {
                        $query->where('user_id', $user->id); // Assuming 'user_id' is on the 'patients' table
                    });
                });
        }

        // If the user is a doctor, show only payments for appointments they are assigned to
        elseif ($user->role === 'doctor') {
            return Payment::query()
                ->whereHas('appointment', function ($query) use ($user) {
                    // Filter payments related to the doctor's appointments
                    $query->whereHas('doctor', function ($query) use ($user) {
                        $query->where('user_id', $user->id); // Assuming 'user_id' is on the 'doctors' table
                    });
                });
        }

        // If the user is an admin, show all payments
        elseif ($user->role === 'admin') {
            return Payment::query();  // No filters, show all payments
        }

        // If the user role is not recognized, return no records
        return Payment::query()->whereRaw('0 = 1'); // No records will match this
    }
}
