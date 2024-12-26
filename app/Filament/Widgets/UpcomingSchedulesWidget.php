<?php

namespace App\Filament\Widgets;

use App\Models\Schedule;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class UpcomingSchedulesWidget extends BaseWidget
{
    protected static ?string $heading = 'Upcoming Schedules';
    protected static ?int $sort = 5;
    protected function getTableQuery(): Builder
    {
        return Schedule::query()
            ->where('date', '>', Carbon::today())
            ->with(['doctor.user', 'doctor.specialization'])
            ->orderBy('date');
    }


    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('doctor.user.name')
                ->label('Doctor Name')
                ->searchable(),
            TextColumn::make('doctor.specialization.name')
                ->label('Specialization'),
            TextColumn::make('date')
                ->label('Date')
                ->date()
                ->color('success'),
        

        ];
    }
}
