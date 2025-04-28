<?php

namespace App\Filament\Resources\TaskCommentResource\Pages;

use App\Filament\Resources\TaskCommentResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTaskComments extends ManageRecords
{
    protected static string $resource = TaskCommentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
