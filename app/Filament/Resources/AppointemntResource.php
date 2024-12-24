<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointemntResource\Pages;
use App\Filament\Resources\AppointemntResource\RelationManagers;
use App\Models\Appointment;
use App\Services\AppointmentService;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Schedule;
use App\Models\Specialization;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;


class AppointemntResource extends Resource
{
    protected static ?string $model = Appointment::class;


    protected static ?string $navigationIcon = 'heroicon-o-calendar';


    protected static ?string $navigationGroup = 'Appointment Management';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('specialization_id')
                    ->label('Specialization')
                    ->options(fn() => Specialization::pluck('name', 'id'))
                    ->searchable()
                    ->reactive()
                    ->required()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('doctor_id', null); // Reset doctor field
                    }),

                Forms\Components\Select::make('doctor_id')
                    ->label('Doctor')
                    ->options(function ($get) {
                        $specializationId = $get('specialization_id');
                        if ($specializationId) {
                            return Doctor::where('specialization_id', $specializationId)
                                ->with('user')
                                ->get()
                                ->mapWithKeys(function ($doctor) {
                                    return [
                                        $doctor->id => optional($doctor->user)->name ?? 'Unknown Doctor',
                                    ];
                                });
                        }
                        return [];
                    })
                    ->required()
                    ->searchable()
                    ->reactive() // This makes the component react to changes.
                    ->afterStateUpdated(function (callable $set) {
                        // Reset the schedule_id field when doctor_id changes
                        $set('schedule_id', null);
                    }),
                Forms\Components\Select::make('patient_id')
                    ->label('Patient')
                    ->options(function () {
                        return Patient::with('user')
                            ->get()
                            ->filter(fn($patient) => optional($patient->user)->name) 
                            ->mapWithKeys(function ($patient) {
                                return [
                                    $patient->id => $patient->user->name, 
                                ];
                            });
                    })
                    ->hidden(fn($get) => Auth::user()->role==='patient') // Hide for patient role
                    ->required(),
                Forms\Components\Select::make('schedule_id')
                    ->label('Appointment Date')
                    ->options(function ($get) {
                        $doctorId = $get('doctor_id'); // Get the currently selected doctor
                        if ($doctorId) {
                            return Schedule::where('doctor_id', $doctorId)
                                ->where('availability', 'available') // Ensure only available schedules
                                ->get()
                                ->mapWithKeys(function ($schedule) {
                                    return [
                                        $schedule->id => $schedule->date, // Map ID to the date
                                    ];
                                });
                        }
                        return [];
                    })
                    ->required()
                    ->reactive()
                    ->placeholder('Select a schedule'),

                Forms\Components\Select::make('start_time')
                    ->label('Booking Time')
                    ->options(function ($get) {
                        $scheduleId = $get('schedule_id'); // Get the selected schedule ID
                        if ($scheduleId) {
                            $schedule = Schedule::find($scheduleId);
                            if ($schedule) {
                                // Fetch booked slots for the selected schedule
                                $bookedSlots = Appointment::where('schedule_id', $scheduleId)
                                    ->pluck('start_time')
                                    ->toArray();

                                // Generate all possible slots
                                $appointmentService = new AppointmentService();
                                $slots = $appointmentService->generateTimeSlots($schedule->start_time, $schedule->end_time);

                                // Filter out booked slots
                                $availableSlots = collect($slots)->filter(function ($slot) use ($bookedSlots) {
                                    return !in_array($slot, $bookedSlots);
                                });

                                return $availableSlots->mapWithKeys(function ($slot) {
                                    return [$slot => $slot];
                                });
                            }
                        }
                        return [];
                    })
                    ->required()
                    ->reactive()
                    ->placeholder('Select a time slot'),

                Forms\Components\Hidden::make('status')
                    ->default('pending')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('patient_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('doctor_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('schedule_id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time'),

                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->hidden()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->hidden()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->hidden(fn($record) => $record->status === "booked" || $record->status === "completed"),

                Tables\Actions\DeleteAction::make()
                    ->hidden(fn($record) => $record->status === "booked"),
                Tables\Actions\Action::make('book')
                    ->label('Mark as Booked')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($record) {


                        $record->status = 'booked';
                        $record->save();
                    })
                    ->hidden(fn($record) => $record->status === "booked" || $record->status === "completed")
                    ->visible(fn() => in_array(Auth::user()->role, ['admin', 'doctor'])),


                Tables\Actions\Action::make('complete')
                    ->label('Mark as Completed')
                    ->icon('heroicon-o-check-circle')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->status = 'completed';
                        $record->save();
                    })
                    ->hidden(fn($record) => $record->status == 'pending' || $record->status == 'completed')
                    ->visible(fn() => in_array(Auth::user()->role, ['admin', 'doctor'])),


            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([

                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointemnts::route('/'),
            'create' => Pages\CreateAppointemnt::route('/create'),
            'view' => Pages\ViewAppointemnt::route('/{record}'),
            'edit' => Pages\EditAppointemnt::route('/{record}/edit'),
        ];
    }
}
