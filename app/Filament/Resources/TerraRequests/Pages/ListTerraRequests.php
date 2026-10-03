<?php

namespace App\Filament\Resources\TerraRequests\Pages;

use App\Filament\Resources\TerraRequests\TerraRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTerraRequests extends ListRecords
{
    protected static string $resource = TerraRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
