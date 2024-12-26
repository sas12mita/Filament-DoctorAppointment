<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointemntResource\Pages;
use App\Filament\Resources\AppointemntResource\RelationManagers;
use App\Models\Appointment;
use App\Services\AppointmentService;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Payment;
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
use Illuminate\Support\Carbon;


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
                    ->hidden(fn($get) => Auth::user()->role === 'patient') // Hide for patient role
                    ->required(),
                Forms\Components\Select::make('schedule_id')
                    ->label('Appointment Date')
                    ->options(function ($get) {
                        $doctorId = $get('doctor_id'); // Get the currently selected doctor
                        if ($doctorId) {
                            return Schedule::where('doctor_id', $doctorId)
                                ->where('availability', 'available') // Ensure only available schedules
                                ->whereDate('date', '>', Carbon::today())
                                ->get()
                                ->mapWithKeys(function ($schedule) {
                                    return [
                                        $schedule->id => $schedule->date,
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
                        $appointmentDate = $get('appointment_date'); // Ensure this field exists in your form

                        if ($scheduleId) {
                            $schedule = Schedule::find($scheduleId);
                            if ($schedule) {
                                // Fetch booked slots for the selected schedule
                                $bookedSlots = Appointment::where('schedule_id', $scheduleId)
                                    ->whereTime('start_time', '>=', Carbon::now()->format('H:i'))
                                    ->pluck('start_time')
                                    ->toArray();

                                $appointmentService = new AppointmentService();

                                // Determine if the appointment date is today
                                $isToday = Carbon::parse($appointmentDate)->isToday();

                                // Generate slots with the $excludePastSlots flag
                                $slots = $appointmentService->generateTimeSlots(
                                    $schedule->start_time,
                                    $schedule->end_time,
                                    15,
                                    $isToday
                                );

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
                Tables\Columns\TextColumn::make('patient.user.name')
                    ->label('Patient')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('doctor.user.name')
                    ->label('Doctor')
                    ->numeric()
                    ->sortable(),


                    
              
                // Tables\Columns\TextColumn::make('schedule_id')
                //     ->label('Appointment Date')
                //     ->relationship('schedule.date')
                //     ->sortable(),
                Tables\Columns\TextColumn::make('schedule.date')
                ->label('Appointment Date')
                
                ->sortable()
                ->formatStateUsing(fn ($record) => $record->schedule ? Carbon::parse($record->schedule->date)->format('Y-m-d') : 'N/A'),
                  
                Tables\Columns\TextColumn::make('start_time'),

                Tables\Columns\TextColumn::make('status')
                ->badge()
                ->color(function ($record) {
                    return match ($record->status) {
                        'booked' => 'success',
                        'pending' => 'danger',
                        'completed' => 'success',
                    };
                })->searchable(),
                Tables\Columns\TextColumn::make('payment.status')
                ->badge()
                ->label('Payment Status')
                ->numeric()
                ->color(function ($record) {
                    return match ($record->payment->status) {
                        'paid' => 'success',
                        'unpaid' => 'danger',
                    };
                })->searchable()
                ->sortable(),
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
                Tables\Actions\ActionGroup::make([

                    Tables\Actions\ViewAction::make(),

                    Tables\Actions\EditAction::make()
                        ->hidden(fn($record) => in_array($record->status, ['completed'])),

                    Tables\Actions\DeleteAction::make()
                        ->hidden(fn($record) => $record->status === 'booked'),

                    Tables\Actions\Action::make('book')
                        ->label('Pay via stripe ')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            // $url = url('docbook/appointments/stripe-payment/{record}', ['id'=> $record->payment->id]);
                            // return $url;
                            $payment = Payment::where('appointment_id', $record->id)->first();
                            return redirect(route('stripeform', ['payment' => $payment]));
                        })
                        ->hidden(fn($record) => in_array($record->status, ['booked', 'completed']))
                        ->visible(fn() => in_array(Auth::user()->role, ['admin', 'doctor','patient'])),

                    Tables\Actions\Action::make('complete')
                        ->label('Mark as Completed')
                        ->icon('heroicon-o-check-circle')
                        ->color('primary')
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            $record->status = 'completed';
                            $record->save();
                        })
                        ->hidden(fn($record) => in_array($record->status, ['pending', 'completed']))
                        ->visible(fn() => in_array(Auth::user()->role, ['admin', 'doctor'])),
                ])
                    ->label('Actions') // Label for the action group dropdown
                    ->icon('heroicon-o-ellipsis-vertical'), // Icon for the action group dropdown
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
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
