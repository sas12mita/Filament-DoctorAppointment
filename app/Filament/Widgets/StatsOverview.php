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


class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [

            Stat::make('Total Patients', Patient::count())
                ->icon('heroicon-o-user-plus') 
                ->color('primary') // Sets the background color to green (success color)
                ->description('Registered Patients') // Add a small description
                ->descriptionIcon('heroicon-m-arrow-trending-up') // Add a description icon
                ->extraAttributes(['class' => 'flex items-center','style' => 'background-color:rgb(203, 203, 248)']),

          
                Stat::make('Total Doctor', Doctor::count())
                ->icon('heroicon-o-user') // Keep the icon for now, we'll style it
               
                ->color('primary') // Sets the background color to green (success color)
                ->description('Registered Doctor') // Add a small description
                ->descriptionIcon('heroicon-m-arrow-trending-up') // Add a description icon
                ->extraAttributes(['class' => 'flex items-center','style' => 'background-color:rgb(242, 250, 192)']),

                Stat::make('Total Schedule', Schedule::count())
                ->icon('heroicon-o-calendar')
                ->color('primary') // Sets the background color to green (success color)
                ->description('Doctor/s Schedule') // Add a small description
                ->descriptionIcon('heroicon-m-arrow-trending-up') // Add a description icon
                ->extraAttributes(['class' => 'flex items-center','style' => 'background-color:rgb(208, 255, 244)']),

                  Stat::make('Total Specility', Specialization::count())
                ->icon('heroicon-o-document') 
                ->color('primary') // Sets the background color to green (success color)
                ->description('Doctor/s Specility') // Add a small description
                ->descriptionIcon('heroicon-m-arrow-trending-up') // Add a description icon
                ->extraAttributes(['class' => 'flex items-center','style' => 'background-color:rgb(245, 218, 249)']),

                Stat::make('Pending Appointment', Appointment::where('status', 'pending')->count()) 
                ->icon('heroicon-o-calendar-date-range') 
                ->color('primary') // Sets the background color to green (success color)
                ->description('Appointment') // Add a small description
                ->descriptionIcon('heroicon-m-arrow-trending-up') // Add a description icon
                ->extraAttributes(['class' => 'flex items-center','style' => 'background-color:rgb(249, 198, 202)']),
                
                Stat::make('Booked Appointment', Appointment::where('status', 'booked')->count()) 
                ->icon('heroicon-o-calendar-date-range') 
                ->color('primary') // Sets the background color to green (success color)
                ->description('Appointment') // Add a small description
                ->descriptionIcon('heroicon-m-arrow-trending-up') // Add a description icon
                ->extraAttributes(['class' => 'flex items-center','style' => 'background-color:rgb(210, 247, 206)']),
        
                Stat::make('Completed Appointment', Appointment::where('status', 'completed')->count()) 
                ->icon('heroicon-o-calendar-date-range') 
                ->color('info')
                ->description('Appointment') // Add a small description
                ->descriptionIcon('heroicon-m-arrow-trending-up') // Add a description icon
                ->extraAttributes(['class' => 'flex items-center','style' => 'background-color:rgb(176, 219, 242)'])
                ];
    }

}

