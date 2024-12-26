<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Filament\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    
    protected static ?string $navigationGroup = 'Appointment Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('appointment.schedule.date')
                ->label('Appointment Date')
                
                ->sortable()
                ->formatStateUsing(fn ($record) => $record->appointment->schedule ? Carbon::parse($record->appointment->schedule->date)->format('Y-m-d') : 'N/A'),
                  
                // Tables\Columns\TextColumn::make('appointment_id')
                // ->label('Appointment Date')
                
                // ->sortable()
                // ->formatStateUsing(fn ($record) => $record->schedule ? Carbon::parse($record->appointment->schedule->date)->format('Y-m-d') : 'N/A'),
                 
                Tables\Columns\TextColumn::make('amount')->sortable(),
                Tables\Columns\TextColumn::make('appointment.patient.user.name')->sortable()->label('Patient'),
                Tables\Columns\TextColumn::make('status')->sortable()
                ->badge()
                ->color(function ($record) {
                    return match ($record->status) {
                        'paid' => 'success',
                        'unpaid' => 'danger',
                    };
                })->searchable(),
                Tables\Columns\TextColumn::make('payment_method')
                ->label('Payment Method')
                ->getStateUsing(function ($record) {
                    // Check if payment_method is empty or null
                    return $record->payment_method ?: 'No Payment';
                })
                ->searchable()
                ->sortable(),      
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('Add Payment')
                ->icon('heroicon-o-credit-card')
                ->label('Add Payment')
                    ->action(function ($record) {
                        // $url = url('docbook/appointments/stripe-payment/{record}', ['id'=> $record->payment->id]);
                        // return $url;
                        $payment = Payment::where('id', $record->id)->first();
                        return redirect(route('stripeform', ['payment' => $payment]));
                })
                ->disabled(fn ($record) => $record->status === 'paid')  
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
