<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\ChartWidget;

class ProjectProgressChart extends ChartWidget
{
    use HasWidgetShield;

    protected static ?string $heading = 'Project Status Overview';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $projectsByStatus = Project::query()
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Projects by Status',
                    'data' => [
                        $projectsByStatus['planning'] ?? 0,
                        $projectsByStatus['in_progress'] ?? 0,
                        $projectsByStatus['completed'] ?? 0,
                        $projectsByStatus['on_hold'] ?? 0,
                        $projectsByStatus['cancelled'] ?? 0,
                    ],
                    'backgroundColor' => [
                        '#3b82f6', // blue for planning
                        '#f59e0b', // amber for in_progress
                        '#10b981', // green for completed
                        '#6b7280', // gray for on_hold
                        '#ef4444', // red for cancelled
                    ],
                ],
            ],
            'labels' => ['Planning', 'In Progress', 'Completed', 'On Hold', 'Cancelled'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
            'scales' => [
                'x' => [
                    'display' => false,
                ],
                'y' => [
                    'display' => false,
                ],
            ],
        ];
    }
}
