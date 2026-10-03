<?php

namespace App\Filament\Resources\CreateContactsTables\Pages;

use App\Filament\Resources\CreateContactsTables\CreateContactsTableResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCreateContactsTable extends EditRecord
{
    protected static string $resource = CreateContactsTableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
