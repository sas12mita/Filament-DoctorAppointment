<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientReportResource\Pages;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\PatientReport;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class PatientReportResource extends Resource
{
    protected static ?string $model = PatientReport::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-up';
    
    protected static ?string $navigationGroup = 'Appointment Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('appointment_id')
                    ->label('Appointment')
                    ->options(function () {
                        $user = Auth::user();

                        // Admin can see all completed appointments
                        if ($user->role == "admin") {
                            return Appointment::where('status', 'completed')
                                ->get()
                                ->mapWithKeys(function ($appointment) {
                                    return [
                                        $appointment->id => "{$appointment->patient->user->name} has Appointment on {$appointment->schedule->date} at {$appointment->start_time}"
                                    ];
                                });
                        }

                        // Doctors can only see their own completed appointments
                        if ($user->role == "doctor") {
                            $doctor = Doctor::where('user_id', $user->id)->first();

                            if ($doctor) {
                                return Appointment::where('doctor_id', $doctor->id)
                                    ->where('status', 'completed')
                                    ->get()
                                    ->mapWithKeys(function ($appointment) {
                                        return [
                                            $appointment->id => "{$appointment->patient->user->name} has Appointment on {$appointment->schedule->date} at {$appointment->start_time}"
                                        ];
                                    });
                            }
                        }
                        if ($user->role == "patient") {
                            $patient = Patient::where('user_id', $user->id)->first();

                            if ($patient) {
                                return Appointment::where('patient_id', $patient->id)
                                    ->where('status', 'completed')
                                    ->get()
                                    ->mapWithKeys(function ($appointment) {
                                        return [
                                            $appointment->id => "{$appointment->patient->user->name} has Appointment on {$appointment->schedule->date} at {$appointment->start_time}"
                                        ];
                                    });
                            }
                        }

                        // Default empty for other roles or no matching doctor
                        return [];
                    })
                    ->searchable()
                    ->required(),

                Forms\Components\FileUpload::make('file')
                    ->label('Report File')
                    ->disk('public') // Ensure correct disk is used
                    ->directory('reports') // Ensure the directory exists
                    ->preserveFilenames()
                    ->downloadable()
                    ->required() // Ensures a file is selected
                    ->acceptedFileTypes(['application/pdf'])


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('appointment_id')
                    ->label('Appointment ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('file')
                    ->label('Report File')
                    ->getStateUsing(fn ($record) => basename($record->file))
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPatientReports::route('/'),
            'create' => Pages\CreatePatientReport::route('/create'),
            'view' => Pages\ViewPatientReport::route('/{record}'),
            'edit' => Pages\EditPatientReport::route('/{record}/edit'),
        ];
    }
}
