<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class StatsOverviewWidget extends BaseWidget
{
    use HasWidgetShield;
    protected function getStats(): array
    {
        return [
            Stat::make('Total Projects', Project::count())
                ->description('All time projects')
                ->descriptionIcon('heroicon-m-folder')
                ->chart([7, 3, 4, 5, 6, 3, 5])
                ->color('success'),

            Stat::make('Active Projects', Project::where('status', 'in_progress')->count())
                ->description(Project::where('status', 'planning')->count() . ' in planning')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('warning'),

            Stat::make('Total Tasks', Task::count())
                ->description(Task::where('status', 'in_progress')->count() . ' in progress')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->chart([3, 5, 2, 7, 1, 4, 6])
                ->color('primary'),

            Stat::make('Overdue Tasks', Task::where('due_date', '<', now())
                ->where('status', '!=', 'done')
                ->count())
                ->description('Need attention')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),
        ];
    }
}
