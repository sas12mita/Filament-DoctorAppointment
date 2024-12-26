<?php
namespace App\Filament\Widgets;

use App\Models\Appointment;
use App\Models\Schedule;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class WeeklyAppointmentsChart extends ChartWidget
{
    protected static ?string $heading = 'Appointments for the Week';
    protected static ?int $sort = 2;

    // Implement the getType() method
    protected function getType(): string
    {
        return 'line';  // You can use 'line', 'bar', etc. depending on the type of chart you want
    }

    public function getData(): array
    {
        // Fetch appointments grouped by schedule date
        $appointments = Schedule::withCount(['appointments as appointment_count' => function ($query) {
            $query->where('appointments.status', '!=', 'completed'); // Optional filter
        }])
        ->orderBy('date')
        ->get(['date', 'appointment_count']);

        // Prepare the data for the chart
        $dates = $appointments->pluck('date')->map(function ($date) {
            return Carbon::parse($date)->format('l'); // Get the day name (e.g., Monday, Tuesday)
        })->toArray();
        
        $counts = $appointments->pluck('appointment_count')->toArray();

        return [
            'labels' => $dates,
            'datasets' => [
                [
                    'label' => 'Appointments',
                    'data' => $counts,
                    'backgroundColor' => '#36A2EB',
                ],
            ],
        ];
    }
}
