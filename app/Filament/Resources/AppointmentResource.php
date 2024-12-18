<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\AppointmentResource\RelationManagers;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Schedule;
use App\Models\Specialization;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
            ->reactive()
            ->afterStateUpdated(function ($state, callable $set) {
                $set('appointment_date', null); // Reset appointment date
            }),

            Forms\Components\Select::make('patient_id')
            ->label('Patient')
            ->options(function () {
                return Patient::with('user')
                    ->get()
                    ->filter(fn($patient) => optional($patient->user)->name) // Only include patients with valid names
                    ->mapWithKeys(function ($patient) {
                        return [
                            $patient->id => $patient->user->name, // Fetch user name
                        ];
                    });
            })
            ->required(),

        Forms\Components\Select::make('appointment_date')
            ->label('Appointment Date')
            ->required()
            ->options(function ($get) {
                $doctorId = $get('doctor_id');
                if ($doctorId) {
                    return Schedule::where('doctor_id', $doctorId)
                        ->pluck('date', 'date'); // Key and value are both date
                }
                return [];
            }),

        Forms\Components\TimePicker::make('start_time')
            ->required(),

        Forms\Components\TimePicker::make('end_time')
            ->required(),

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
                Tables\Columns\TextColumn::make('appointment_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time'),
                Tables\Columns\TextColumn::make('end_time'),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'view' => Pages\ViewAppointment::route('/{record}'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}
