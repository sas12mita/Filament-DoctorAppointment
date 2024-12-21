<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Illuminate\Support\Carbon;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TodayAppointments extends BaseWidget
{
    protected static ?string $heading = 'Today\'s Appointments'; // Set the widget heading
    public function table(Table $table): Table
    {
        return $table
            ->query($this->getQuery()) // Fetch today's appointments
            ->columns($this->getColumns()) // Define the table columns
            ->defaultSort('appointment_time', 'asc'); // Default sorting by time
    }

    /**
     * Query to fetch today's appointments.
     */
    protected function getQuery(): Builder
    {
        
        $appointment= Appointment::whereHas('schedule', function (Builder $query) {
            $query->whereDate('date', Carbon::today()); // Adjust to match the schedule's date column
        });
        return $appointment;
    }

    /**
     * Define the columns for the table.
     */
    protected function getColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('patient.user.name')
                ->label('Patient Name')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('doctor.user.name')
                ->label('Doctor Name')
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
