<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\ChartWidget;

class TasksByStatusChart extends ChartWidget
{
    use HasWidgetShield;

    protected static ?string $heading = 'Tasks by Status';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $tasksByStatus = Task::query()
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Tasks',
                    'data' => [
                        $tasksByStatus['todo'] ?? 0,
                        $tasksByStatus['in_progress'] ?? 0,
                        $tasksByStatus['done'] ?? 0,
                    ],
                    'backgroundColor' => [
                        '#f59e0b', // amber for todo
                        '#3b82f6', // blue for in_progress
                        '#10b981', // green for done
                    ],
                ],
            ],
            'labels' => ['To Do', 'In Progress', 'Done'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }
}
