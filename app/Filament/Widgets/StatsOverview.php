<?php
namespace App\Filament\Widgets;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Schedule;
use App\Models\Specialization;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $stats = [
            Stat::make('Total Patients', Patient::count())
                ->icon('heroicon-o-user-plus') 
                ->description('Registered Patients')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),

            Stat::make('Total Doctors', Doctor::count())
                ->icon('heroicon-o-user')
                ->description('Registered Doctors')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),

            Stat::make('Total Schedules', Schedule::count())
                ->icon('heroicon-o-calendar')
                ->description('Doctors Schedules')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),

            Stat::make('Total Specializations', Specialization::count())
                ->icon('heroicon-o-document')
                ->description('Doctors Specializations')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
        ];

        if (Auth::user()->role == "doctor") {
            $user_id = Auth::user()->id;
            $doctor = Doctor::where('user_id', $user_id)->first();

            if ($doctor) {
                $stats[] = Stat::make('Pending Appointments', Appointment::where('status', 'pending')->where('doctor_id', $doctor->id)->count()) 
                    ->icon('heroicon-o-calendar')
                    ->description('Appointments')
                    ->descriptionIcon('heroicon-m-arrow-trending-up');

                $stats[] = Stat::make('Booked Appointments', Appointment::where('status', 'booked')->where('doctor_id', $doctor->id)->count()) 
                    ->icon('heroicon-o-calendar')
                    ->color('primary')
                    ->description('Appointments')
                    ->descriptionIcon('heroicon-m-arrow-trending-up');

                $stats[] = Stat::make('Completed Appointments', Appointment::where('status', 'completed')->where('doctor_id', $doctor->id)->count()) 
                    ->icon('heroicon-o-calendar')
                    ->color('info')
                    ->description('Appointments')
                    ->descriptionIcon('heroicon-m-arrow-trending-up');
            }
        }

        if (Auth::user()->role == "admin") {
            $stats[] = Stat::make('Pending Appointments', Appointment::where('status', 'pending')->count()) 
                ->icon('heroicon-o-calendar')
                ->description('Appointments')
                ->descriptionIcon('heroicon-m-arrow-trending-up');

            $stats[] = Stat::make('Booked Appointments', Appointment::where('status', 'booked')->count()) 
                ->icon('heroicon-o-calendar')
                ->description('Appointments')
                ->descriptionIcon('heroicon-m-arrow-trending-up');

            $stats[] = Stat::make('Completed Appointments', Appointment::where('status', 'completed')->count()) 
                ->icon('heroicon-o-calendar')
                ->description('Appointments')
                ->descriptionIcon('heroicon-m-arrow-trending-up');
        }

        return $stats;
    }
}
