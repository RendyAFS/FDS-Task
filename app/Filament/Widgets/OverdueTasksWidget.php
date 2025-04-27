<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class OverdueTasksWidget extends BaseWidget
{
    use HasWidgetShield;

    protected static ?string $heading = 'Overdue Tasks';

    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Task::query()
                    ->with(['project', 'assignedUser'])
                    ->where('due_date', '<', now())
                    ->where('status', '!=', 'done')
                    ->orderBy('due_date', 'asc')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('project.name')
                    ->label('Project')
                    ->searchable()
                    ->limit(20),
                Tables\Columns\TextColumn::make('assignedUser.name')
                    ->label('Assigned To')
                    ->default('Unassigned'),
                Tables\Columns\TextColumn::make('due_date')
                    ->date()
                    ->color('danger')
                    ->description(fn (Task $record): string =>
                        now()->diffForHumans($record->due_date, true) . ' overdue'
                    ),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'danger' => 'todo',
                        'warning' => 'in_progress',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'todo' => 'To Do',
                        'in_progress' => 'In Progress',
                        default => $state,
                    }),
                Tables\Columns\BadgeColumn::make('priority')
                    ->colors([
                        'danger' => 'high',
                        'warning' => 'medium',
                        'success' => 'low',
                    ])
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
            ])
            ->paginated(false)
            ->emptyStateHeading('No overdue tasks')
            ->emptyStateDescription('Great! All tasks are on schedule.')
            ->emptyStateIcon('heroicon-o-check-circle');
    }
}
