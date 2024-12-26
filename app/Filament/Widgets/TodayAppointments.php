<?php
namespace App\Filament\Widgets;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class TodayAppointments extends BaseWidget
{
    protected static ?string $heading = 'Today\'s Appointments'; // Set the widget heading
    protected static ?int $sort = 4;
    public function table(Table $table): Table
    {
        return $table
            ->query($this->getQuery()) // Fetch today's appointments
            ->columns($this->getColumns()) // Define the table columns
            ->defaultSort('start_time', 'asc'); // Default sorting by time
    }

    /**
     * Query to fetch today's appointments.
     */
    protected function getQuery(): Builder
    {
        // If the user is an admin, show all today's appointments
        if (Auth::user()->role == 'admin') {
            $appointment= Appointment::whereHas('schedule', function (Builder $query) {
                $query->whereDate('date', Carbon::today()); // Adjust to match the schedule's date column
            });
        }

        // If the user is a doctor, show only their appointments
        if (Auth::user()->role == 'doctor') {
            // Fetch the doctor_id from the doctor table based on the logged-in user's id
            $user_id=Auth::user()->id;
            $doctor=Doctor::where('user_id',$user_id)->first();
            $appointment=  Appointment::whereHas('schedule', function (Builder $query) {
                $query->whereDate('date', Carbon::today()); // Adjust to match the schedule's date column
            })
            ->where('doctor_id', $doctor->id); // Filter appointments by the logged-in doctor's id
        }
        if (Auth::user()->role == 'patient') {
            // Fetch the doctor_id from the doctor table based on the logged-in user's id
            $user_id=Auth::user()->id;
            $patient=Patient::where('user_id',$user_id)->first();
          

            $appointment=  Appointment::whereHas('schedule', function (Builder $query) {
                $query->whereDate('date', Carbon::today()); // Adjust to match the schedule's date column
            })
            ->where('patient_id', $patient->id); // Filter appointments by the logged-in doctor's id
        }

        // Default case: return no appointments
        return $appointment;
    }

    /**
     * Define the columns for the table.
     */
    protected function getColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('patient.user.name')
                ->label('Patient')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('doctor.user.name')
                ->label('Doctor')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('start_time')
                ->label('Time')
                ->sortable()
                ->extraAttributes(function ($record) {
                    // Dynamically style based on condition
                    return [
                        'class' => 'bg-green-100',
                        'style' => 'background-color: rgb(110, 242, 62); color: white; padding-top: 5px; padding-bottom:5px; padding-left: 10px;border-radius: 5px;',
                    ];
                }),
        ];
    }
}
