<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use App\Models\User;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class UserTaskPerformanceChart extends ChartWidget
{
    use HasWidgetShield;

    protected static ?string $heading = 'User Task Performance';

    protected static ?int $sort = 6;

    protected function getData(): array
    {
        $userTaskStats = User::query()
            ->select('users.name')
            ->selectRaw('COUNT(CASE WHEN tasks.status = "done" THEN 1 END) as completed')
            ->selectRaw('COUNT(CASE WHEN tasks.status = "in_progress" THEN 1 END) as in_progress')
            ->selectRaw('COUNT(CASE WHEN tasks.status = "todo" THEN 1 END) as todo')
            ->leftJoin('tasks', 'users.id', '=', 'tasks.assigned_to')
            ->groupBy('users.id', 'users.name')
            ->having(DB::raw('COUNT(tasks.id)'), '>', 0)
            ->orderBy(DB::raw('COUNT(tasks.id)'), 'desc')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Completed',
                    'data' => $userTaskStats->pluck('completed')->toArray(),
                    'backgroundColor' => '#10b981',
                ],
                [
                    'label' => 'In Progress',
                    'data' => $userTaskStats->pluck('in_progress')->toArray(),
                    'backgroundColor' => '#3b82f6',
                ],
                [
                    'label' => 'To Do',
                    'data' => $userTaskStats->pluck('todo')->toArray(),
                    'backgroundColor' => '#f59e0b',
                ],
            ],
            'labels' => $userTaskStats->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
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
                    'stacked' => true,
                ],
                'y' => [
                    'stacked' => true,
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
        ];
    }
}
