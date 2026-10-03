<?php

namespace App\Filament\Resources\CreateContactsTables\Pages;

use App\Filament\Resources\CreateContactsTables\CreateContactsTableResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCreateContactsTables extends ListRecords
{
    protected static string $resource = CreateContactsTableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
