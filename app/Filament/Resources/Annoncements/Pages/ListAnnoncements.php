<?php

namespace App\Filament\Resources\Annoncements\Pages;

use App\Filament\Resources\Annoncements\AnnoncementsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAnnoncements extends ListRecords
{
    protected static string $resource = AnnoncementsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
