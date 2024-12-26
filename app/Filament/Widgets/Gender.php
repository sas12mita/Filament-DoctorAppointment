<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class Gender extends ChartWidget
{
    protected static ?int $sort = 3;
    protected static ?string $heading = 'Gender Distribution';
    protected static ?string $maxHeight = '200px';

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getData(): array
    {
        $genderCounts = DB::table('users')
            ->join('patients', 'users.id', '=', 'patients.user_id')
            ->select('users.gender', DB::raw('COUNT(users.gender) as count'))
            ->groupBy('users.gender')
            ->pluck('count', 'users.gender');

        $total = $genderCounts->sum();
        $labels = $genderCounts->keys()->toArray();
        $data = $genderCounts->values()->toArray();

        $colors = ['#3498db', '#e74c3c', '#2ecc71'];

        $formattedLabels = array_map(function ($label, $count) use ($total) {
            $percentage = $total > 0 ? ($count / $total) * 100 : 0;
            $roundedPercentage = round($percentage / 20) * 20;

            if ($percentage < 0.1) {
                $formattedLabel = $label;
            } elseif (abs($percentage - 0.2) < 0.1) {
                $formattedLabel = "$label (0.2%)";
            } else {
                $formattedLabel = "$label ({$roundedPercentage}%)";
            }

            return $formattedLabel;
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
            'options' => [
                'responsive' => true,
                'maintainAspectRatio' => false,
                'plugins' => [
                    'legend' => [
                        'position' => 'top',
                    ],
                ],
                'cutoutPercentage' => 60, // Optional: Adjust the cutout percentage for a donut chart
                'aspectRatio' => 1,       // Optional: Maintain a 1:1 aspect ratio (square chart)
            ],
        ];
    }
}