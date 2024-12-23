<?php
namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class Zender extends ChartWidget
{
    protected static ?string $heading = 'Gender Distribution';

    // Specify the chart type
    protected function getType(): string
    {
        return 'pie';
    }

    protected function getData(): array
    {
        // Query gender distribution from the database
        $genderCounts = DB::table('users')
            ->join('patients', 'users.id', '=', 'patients.user_id')
            ->select('users.gender', DB::raw('COUNT(users.gender) as count'))
            ->groupBy('users.gender')
            ->pluck('count', 'users.gender');

        // Calculate total for percentages
        $total = $genderCounts->sum();

        // Labels and values
        $labels = $genderCounts->keys()->toArray();
        $data = $genderCounts->values()->toArray();

        // Assign different colors to each gender
        $colors = ['#3498db', '#e74c3c', '#2ecc71', '#95a5a6']; // Blue, Red, Green, Gray

        // Format labels to include percentages
        $formattedLabels = array_map(function ($label, $count) use ($total) {
            $percentage = $total > 0 ? round(($count / $total) * 100, 1) : 0;
            return "$label ({$percentage}%)";
        }, $labels, $data);

        return [
            'datasets' => [
                [
                    'label' => 'Gender Distribution',
                    'data' => $data,
                    'backgroundColor' => $colors,
                ],
            ],
            'labels' => $formattedLabels,
        ];
    }
}
