<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientJournalResource\Pages;
use App\Filament\Resources\PatientJournalResource\RelationManagers;
use App\Models\Appointment;
use App\Models\PatientJournal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PatientJournalResource extends Resource
{
    protected static ?string $model = PatientJournal::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';


    protected static ?string $navigationGroup = 'Appointment Management';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('appointment_id')
                    ->label('Patient')
                    ->options(function () {
                        return Appointment::with('patient.user')
                            ->where('status', 'completed')
                            ->get()
                            ->filter(function ($appointment) {
                                // Check if there are no patient_journals associated with this appointment
                                return $appointment->patient_journal;
                            })
                            ->mapWithKeys(function ($appointment) {
                                return [
                                    $appointment->id =>
                                    $appointment->patient->user->name .
                                        ' - Date on ' . $appointment->appointment_date .
                                        ' Start: ' . $appointment->start_time .
                                        ' End: ' . $appointment->end_time
                                ];
                            });
                    }),


                Forms\Components\Textarea::make('report')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('appointment_id')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListPatientJournals::route('/'),
            'create' => Pages\CreatePatientJournal::route('/create'),
            'view' => Pages\ViewPatientJournal::route('/{record}'),
            'edit' => Pages\EditPatientJournal::route('/{record}/edit'),
        ];
    }
}
