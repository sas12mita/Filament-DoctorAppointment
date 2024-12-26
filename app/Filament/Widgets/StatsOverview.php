<?php
namespace App\Filament\Widgets;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Schedule;
use App\Models\Specialization;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Support\Colors\Color; // Correct import!
use Illuminate\Support\Facades\Auth;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // Initialize the stats array
        $stats = [
            Stat::make('Total Patients', Patient::count())
                ->icon('heroicon-o-user-plus') 
                ->color('primary')
                ->description('Registered Patients')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->extraAttributes(['class' => 'flex items-center','style' => 'background-color:rgb(203, 203, 248)']),

            Stat::make('Total Doctor', Doctor::count())
                ->icon('heroicon-o-user')
                ->color('primary')
                ->description('Registered Doctor')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->extraAttributes(['class' => 'flex items-center','style' => 'background-color:rgb(242, 250, 192)']),

            Stat::make('Total Schedule', Schedule::count())
                ->icon('heroicon-o-calendar')
                ->color('primary')
                ->description('Doctor/s Schedule')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->extraAttributes(['class' => 'flex items-center','style' => 'background-color:rgb(208, 255, 244)']),

            Stat::make('Total Specility', Specialization::count())
                ->icon('heroicon-o-document')
                ->color('primary')
                ->description('Doctor/s Specility')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->extraAttributes(['class' => 'flex items-center','style' => 'background-color:rgb(245, 218, 249)']),
        ];
        if(Auth::user()->role=="doctor")
        {
            $user_id=Auth::user()->id;
            $doctor=Doctor::where('user_id',$user_id)->first();
          
            $stats[] = Stat::make('Pending Appointment', Appointment::where('status', 'pending')->where('doctor_id',$doctor->id)->count()) 
            ->icon('heroicon-o-calendar-date-range') 
            ->color('primary')
            ->description('Appointment')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->extraAttributes(['class' => 'flex items-center','style' => 'background-color:rgb(249, 198, 202)']);

        $stats[] = Stat::make('Booked Appointment', Appointment::where('status', 'booked')->where('doctor_id',$doctor->id)->count()) 
            ->icon('heroicon-o-calendar-date-range') 
            ->color('primary')
            ->description('Appointment')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->extraAttributes(['class' => 'flex items-center','style' => 'background-color:rgb(210, 247, 206)']);

        $stats[] = Stat::make('Completed Appointment', Appointment::where('status', 'completed')->where('doctor_id',$doctor->id)->count()) 
            ->icon('heroicon-o-calendar-date-range') 
            ->color('info')
            ->description('Appointment')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->extraAttributes(['class' => 'flex items-center','style' => 'background-color:rgb(176, 219, 242)']);
    
        }

        // Check if the logged-in user is an admin
        if (Auth::user()->role == "admin") {
            $stats[] = Stat::make('Pending Appointment', Appointment::where('status', 'pending')->count()) 
                ->icon('heroicon-o-calendar-date-range') 
                ->color('primary')
                ->description('Appointment')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->extraAttributes(['class' => 'flex items-center','style' => 'background-color:rgb(249, 198, 202)']);

            $stats[] = Stat::make('Booked Appointment', Appointment::where('status', 'booked')->count()) 
                ->icon('heroicon-o-calendar-date-range') 
                ->color('primary')
                ->description('Appointment')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->extraAttributes(['class' => 'flex items-center','style' => 'background-color:rgb(210, 247, 206)']);

            $stats[] = Stat::make('Completed Appointment', Appointment::where('status', 'completed')->count()) 
                ->icon('heroicon-o-calendar-date-range') 
                ->color('info')
                ->description('Appointment')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->extraAttributes(['class' => 'flex items-center','style' => 'background-color:rgb(176, 219, 242)']);
        }

        return $stats;
    }
}
